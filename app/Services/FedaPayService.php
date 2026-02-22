<?php

namespace App\Services;

use App\Models\Ecommerce\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service FedaPay : création de transaction et récupération de l'URL de paiement.
 * Utilisé par l'API pour POST /orders/{id}/payment (app mobile).
 */
class FedaPayService
{
    public static ?string $lastError = null;

    private function baseUrl(): string
    {
        return config('services.fedapay.base_url');
    }

    private function secretKey(): string
    {
        return config('services.fedapay.secret_key', '');
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        $request = Http::withToken($this->secretKey())->acceptJson();
        if (! config('services.fedapay.verify_ssl', true)) {
            $request = $request->withOptions(['verify' => false]);
        }
        return $request;
    }

    /**
     * Crée une transaction FedaPay pour la commande et retourne payment_url, token, transaction_id.
     * Retourne null en cas d'erreur (voir self::$lastError).
     *
     * @return array{payment_url: string, token: string, transaction_id: string}|null
     */
    public function createPaymentForOrder(Order $order): ?array
    {
        self::$lastError = null;

        if ($this->secretKey() === '') {
            self::$lastError = 'FEDAPAY_SECRET_KEY non configurée dans .env';
            Log::error('FedaPay: FEDAPAY_SECRET_KEY non configurée.');
            return null;
        }

        $payload = $this->buildTransactionPayload($order);
        $response = $this->http()->post($this->baseUrl() . '/transactions', $payload);

        if (! $response->successful()) {
            if ($response->status() === 400 && str_contains($response->body(), 'phone_number')) {
                unset($payload['customer']['phone_number']);
                $response = $this->http()->post($this->baseUrl() . '/transactions', $payload);
            }
        }

        if (! $response->successful()) {
            self::$lastError = 'HTTP ' . $response->status() . ' | ' . $response->body();
            Log::error('FedaPay create transaction failed', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        }

        $data = $response->json();
        $transaction = $data['v1/transaction'] ?? $data['transaction'] ?? $data['data'] ?? $data;
        $transactionId = $transaction['id'] ?? $data['id'] ?? null;

        if (empty($transactionId)) {
            self::$lastError = 'Id transaction manquant. Réponse: ' . $response->body();
            return null;
        }

        $paymentUrl = $transaction['payment_url'] ?? null;
        $token = $transaction['payment_token'] ?? $transaction['token'] ?? null;

        if (empty($paymentUrl) || empty($token)) {
            $tokenResponse = $this->http()->post($this->baseUrl() . '/transactions/' . $transactionId . '/token');
            if ($tokenResponse->successful()) {
                $tokenData = $tokenResponse->json();
                $token = $token ?? $tokenData['token'] ?? $tokenData['data']['token'] ?? null;
                $paymentUrl = $paymentUrl ?? $tokenData['url'] ?? $tokenData['data']['url'] ?? null;
            }
        }

        if (empty($token) || empty($paymentUrl)) {
            self::$lastError = 'payment_url ou token manquant dans la réponse FedaPay.';
            return null;
        }

        $order->update(['fedapay_transaction_id' => (string) $transactionId]);

        return [
            'payment_url' => $paymentUrl,
            'token' => $token,
            'transaction_id' => (string) $transactionId,
        ];
    }

    private function buildTransactionPayload(Order $order): array
    {
        $user = $order->user;
        $phoneDigits = preg_replace('/\D/', '', $user->phone ?? '');
        if (strlen($phoneDigits) > 8) {
            $phoneDigits = substr($phoneDigits, -8);
        }
        $localNumber = $phoneDigits !== '' ? $phoneDigits : '66123456';

        $customer = [
            'firstname' => $user->name,
            'email' => $user->email,
            'phone_number' => [
                'number' => (int) $localNumber,
                'country' => 'bj',
            ],
        ];

        return [
            'description' => 'Commande #' . $order->id . ' - ' . config('app.name'),
            'amount' => (int) round((float) $order->total_amount),
            'currency' => ['iso' => 'XOF'],
            'callback_url' => config('app.url') . '/api/orders/' . $order->id . '/payment/callback',
            'custom_metadata' => ['order_id' => (string) $order->id],
            'customer' => $customer,
        ];
    }
}
