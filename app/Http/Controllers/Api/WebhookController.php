<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\OrderResource;
use App\Models\Ecommerce\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class WebhookController extends BaseController
{
    #[OA\Post(
        path: '/webhooks/payment',
        summary: 'Confirmation de paiement (webhook)',
        description: 'Appelé par le prestataire de paiement ou le back-office pour passer une commande en « paid ». Secret requis (PAYMENT_WEBHOOK_SECRET). En production, préférer une signature HMAC.',
        tags: ['Webhooks'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['order_id', 'secret'],
                properties: [
                    new OA\Property(property: 'order_id', type: 'integer', description: 'ID de la commande'),
                    new OA\Property(property: 'secret', type: 'string', description: 'Clé secrète (PAYMENT_WEBHOOK_SECRET)'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Commande marquée comme payée'),
            new OA\Response(response: 400, description: 'Secret invalide ou manquant'),
            new OA\Response(response: 404, description: 'Commande non trouvée'),
            new OA\Response(response: 422, description: 'Commande non en statut pending'),
        ]
    )]
    public function payment(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer',
            'secret' => 'required|string',
        ]);

        $secret = config('services.payment_webhook_secret');
        if (empty($secret) || $request->input('secret') !== $secret) {
            return $this->error('Secret invalide.', 400);
        }

        $order = Order::find($request->input('order_id'));
        if (! $order) {
            return $this->error('Commande non trouvée.', 404);
        }

        if ($order->status !== 'pending') {
            return $this->error('Seules les commandes en attente peuvent être marquées comme payées.', 422);
        }

        $order->update(['status' => 'paid']);
        $order->load('orderItems.product');

        return $this->success(new OrderResource($order), 'Paiement enregistré.', 200);
    }

    /**
     * GET /webhooks/fedapay — permet à FedaPay de valider l'URL (certains dashboards envoient un GET).
     */
    public function fedapayVerify(): JsonResponse
    {
        return response()->json(['message' => 'FedaPay webhook endpoint', 'accept' => 'POST'], 200);
    }

    /**
     * Webhook FedaPay : reçoit les événements (transaction.approved, etc.).
     * Configurer l'URL dans le dashboard FedaPay : https://votre-domaine/api/webhooks/fedapay
     * Optionnel : vérifier X-FedaPay-Signature avec FEDAPAY_WEBHOOK_SECRET.
     */
    #[OA\Post(
        path: '/webhooks/fedapay',
        summary: 'Webhook FedaPay',
        description: 'Appelé par FedaPay lors d\'événements (transaction.approved). Passe la commande en « paid ». Configurer l\'URL dans le dashboard FedaPay (compte test ou live).',
        tags: ['Webhooks'],
        requestBody: new OA\RequestBody(description: 'Payload FedaPay (event, data.transaction...)'),
        responses: [
            new OA\Response(response: 200, description: 'Traité'),
            new OA\Response(response: 400, description: 'Payload invalide'),
        ]
    )]
    public function fedapay(Request $request): JsonResponse
    {
        $payload = $request->all();
        $event = $payload['event'] ?? $payload['type'] ?? null;

        if (! $event) {
            Log::warning('FedaPay webhook: event manquant.', ['payload' => $payload]);
            return response()->json(['message' => 'Event manquant'], 400);
        }

        if ($event !== 'transaction.approved') {
            return response()->json(['message' => 'Event ignoré'], 200);
        }

        $transaction = $payload['data']['transaction'] ?? $payload['data']['v1/transaction'] ?? $payload['transaction'] ?? $payload['data'] ?? null;
        if (! $transaction) {
            Log::warning('FedaPay webhook: transaction manquante.');
            return response()->json(['message' => 'Transaction manquante'], 400);
        }

        $transactionId = is_array($transaction) ? ($transaction['id'] ?? null) : ($transaction->id ?? null);
        $metadata = is_array($transaction) ? ($transaction['custom_metadata'] ?? $transaction['metadata'] ?? []) : ($transaction->custom_metadata ?? $transaction->metadata ?? []);
        $orderId = is_array($metadata) ? ($metadata['order_id'] ?? null) : null;

        $order = null;
        if ($orderId) {
            $order = Order::find($orderId);
        }
        if (! $order && $transactionId) {
            $order = Order::where('fedapay_transaction_id', (string) $transactionId)->first();
        }

        if (! $order) {
            Log::warning('FedaPay webhook: commande non trouvée.', ['order_id' => $orderId, 'transaction_id' => $transactionId]);
            return response()->json(['message' => 'Commande non trouvée'], 200);
        }

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Commande déjà traitée'], 200);
        }

        $order->update(['status' => 'paid']);
        Log::info('FedaPay: commande #' . $order->id . ' marquée comme payée.');

        return response()->json(['message' => 'OK'], 200);
    }
}
