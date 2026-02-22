<?php

use App\Models\Auth\Role;
use App\Models\Auth\User;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\OrderItem;
use App\Models\Ecommerce\Product;
use App\Services\FedaPayService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('fedapay:test-payment {order_id?} {--create-dummy : Créer une commande de test si aucune trouvée}', function (?string $order_id = null) {
    $order = $order_id
        ? Order::with('user')->find($order_id)
        : Order::with('user')->where('status', 'pending')->latest()->first();

    if (! $order && $this->option('create-dummy')) {
        $customerRole = Role::where('name', 'customer')->first();
        $user = $customerRole ? User::where('role_id', $customerRole->id)->first() : null;
        $product = Product::first();
        if (! $user || ! $product) {
            $this->error('Option --create-dummy : il faut au moins un utilisateur (rôle customer) et un produit en base. Lancez les seeders.');
            return 1;
        }
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 100,
            'status' => 'pending',
            'shipping_address' => 'Adresse test',
            'shipping_phone' => '00000000',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 100,
        ]);
        $order->load('user');
        $this->info('Commande de test #' . $order->id . ' créée.');
    }

    if (! $order) {
        $this->error('Aucune commande trouvée. Utilisez un order_id ou: php artisan fedapay:test-payment --create-dummy');
        return 1;
    }

    $this->info('Commande #' . $order->id . ' — Montant: ' . $order->total_amount . ' — Statut: ' . $order->status);

    $secret = config('services.fedapay.secret_key');
    if (empty($secret)) {
        $this->error('FEDAPAY_SECRET_KEY manquante dans .env');
        return 1;
    }

    $this->info('Appel FedaPay (sandbox)...');
    try {
        $service = new FedaPayService;
        $result = $service->createPaymentForOrder($order);
    } catch (\Illuminate\Http\Client\ConnectionException $e) {
        $this->error('Connexion impossible (réseau ou DNS).');
        $this->line($e->getMessage());
        $this->comment('En local, vérifiez Internet / DNS. Ou testez depuis l’app déployée (POST /api/orders/{id}/payment).');
        return 1;
    }

    if (! $result) {
        $this->error('Échec.');
        if (FedaPayService::$lastError) {
            $this->line(FedaPayService::$lastError);
        }
        $this->error('Vérifiez aussi storage/logs/laravel.log');
        return 1;
    }

    $this->newLine();
    $this->info('--- Réponse FedaPay (ce que reçoit l’app) ---');
    $this->line(json_encode([
        'success' => true,
        'message' => 'URL de paiement générée.',
        'data' => $result,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    $this->newLine();
    $this->info('Ouvrir dans le navigateur: ' . $result['payment_url']);
    return 0;
})->purpose('Tester l’initiation paiement FedaPay en local');
