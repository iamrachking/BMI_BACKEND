<?php

namespace App\Services;

use App\Models\Ecommerce\Order;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FedaPayService
{
    public function __construct()
    {
        FedaPay::setApiKey(config('services.fedapay.secret_key'));
        FedaPay::setEnvironment(config('services.fedapay.environment'));
    }

    /**
     * Crée une transaction FedaPay pour une commande et retourne l'URL / token de paiement.
     *
     * @return array{payment_url: string, token: string, transaction_id: string}|null
     */
    public function createPaymentForOrder(Order $order): ?array
    {
        $baseUrl = config('services.fedapay.base_url');
        $secretKey = config('services.fedapay.secret_key');

        if (empty($secretKey)) {
            Log::error('FedaPay: FEDAPAY_SECRET_KEY non configurée.');
            return null;
        }

        $callbackUrl = config('app.url') . '/api/orders/' . $order->id . '/payment/callback';
        $user = $order->user;

        $payload = [
            'description' => 'Commande #' . $order->id . ' - ' . config('app.name'),
            'amount' => (int) round((float) $order->total_amount),
            'currency' => ['iso' => 'XOF'],
            'callback_url' => $callbackUrl,
            'custom_metadata' => [
                'order_id' => (string) $order->id,
            ],
            'customer' => [
                'firstname' => $user->name,
                'email' => $user->email,
                'phone_number' => [
                    'number' => preg_replace('/\D/', '', $user->phone ?? '00000000'),
                    'country' => 'bj',
                ],
            ],
        ];

        try {
            $transaction = Transaction::create($payload);
        } catch (\Throwable $e) {
            Log::error('FedaPay create transaction failed: ' . $e->getMessage());
            return null;
        }

        $transactionId = is_object($transaction) ? ($transaction->id ?? null) : ($transaction['id'] ?? $transaction['data']['id'] ?? null);
        if (empty($transactionId)) {
            Log::error('FedaPay: impossible d\'extraire l\'id de la transaction.');
            return null;
        }

        $tokenResponse = Http::withToken($secretKey)
            ->post($baseUrl . '/transactions/' . $transactionId . '/token');

        if (! $tokenResponse->successful()) {
            Log::error('FedaPay token failed', ['status' => $tokenResponse->status(), 'body' => $tokenResponse->body()]);
            return null;
        }

        $data = $tokenResponse->json();
        $token = $data['token'] ?? $data['data']['token'] ?? null;
        $url = $data['url'] ?? $data['data']['url'] ?? null;

        if (empty($token) || empty($url)) {
            Log::error('FedaPay: token ou url manquant.', ['response' => $data]);
            return null;
        }

        $order->update(['fedapay_transaction_id' => (string) $transactionId]);

        return [
            'payment_url' => $url,
            'token' => $token,
            'transaction_id' => (string) $transactionId,
        ];
    }
}
