<?php

namespace App\Http\Controllers\Api\Ecommerce;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\OrderResource;
use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\Order;
use App\Models\Ecommerce\Product;
use App\Services\FedaPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class OrderController extends BaseController
{
    #[OA\Get(
        path: '/orders',
        summary: 'Liste des commandes',
        description: 'Commandes de l\'utilisateur. Filtres : status (pending, paid, shipped, cancelled), per_page. Réponse paginée.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'status', in: 'query', required: false, schema: new OA\Schema(type: 'string', enum: ['pending', 'paid', 'shipped', 'cancelled'])),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/OrderResponse')),
                new OA\Property(property: 'meta', type: 'object', description: 'Pagination'),
                new OA\Property(property: 'links', type: 'object', description: 'Liens pagination'),
            ])),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Order::query()
            ->where('user_id', $request->user()->id)
            ->with('orderItems.product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = min((int) $request->get('per_page', 15), 50);
        $orders = $query->latest()->paginate($perPage);

        return $this->success(OrderResource::collection($orders), 'OK', 200);
    }

    #[OA\Get(
        path: '/orders/{id}',
        summary: 'Détail d\'une commande',
        description: 'Détail avec adresse de livraison et lignes (items).',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', ref: '#/components/schemas/OrderResponse'),
            ])),
            new OA\Response(response: 403, description: 'Non autorisé'),
            new OA\Response(response: 404, description: 'Non trouvé'),
        ]
    )]
    public function show(Request $request, Order $order): JsonResponse
    {
        if ((string) $order->user_id !== (string) $request->user()->id) {
            return $this->error('Non autorisé.', 403);
        }
        $order->load('orderItems.product');

        return $this->success(new OrderResource($order), 'OK', 200);
    }

    #[OA\Post(
        path: '/orders',
        summary: 'Créer une commande',
        description: 'Crée une commande à partir du panier. Adresse de livraison : fournie dans le body ou prise du profil (PATCH /user). Stock vérifié, panier vidé. Statut initial : pending.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(properties: [
                new OA\Property(property: 'shipping_address', type: 'string', nullable: true, description: 'Adresse de livraison (sinon celle du profil)'),
                new OA\Property(property: 'shipping_phone', type: 'string', nullable: true, description: 'Téléphone livraison (sinon celui du profil)'),
            ])
        ),
        responses: [
            new OA\Response(response: 201, description: 'Commande créée (statut pending)', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string', example: 'Commande créée.'),
                new OA\Property(property: 'data', ref: '#/components/schemas/OrderResponse'),
            ])),
            new OA\Response(response: 422, description: 'Panier vide ou stock insuffisant'),
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'shipping_address' => 'nullable|string|max:1000',
            'shipping_phone' => 'nullable|string|max:50',
        ]);

        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->with('cartItems.product')->first();
        if (! $cart || $cart->cartItems->isEmpty()) {
            return $this->error('Le panier est vide.', 422);
        }

        $total = 0;
        foreach ($cart->cartItems as $item) {
            $product = $item->product;
            if ($product->stock_quantity < $item->quantity) {
                return $this->error("Stock insuffisant pour « {$product->name} ». Disponible : {$product->stock_quantity}.", 422);
            }
            $total += (float) $product->price * $item->quantity;
        }

        $shippingAddress = $request->input('shipping_address') ?? $user->address;
        $shippingPhone = $request->input('shipping_phone') ?? $user->phone;

        $order = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $user, $cart, $total, $shippingAddress, $shippingPhone) {
            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => round($total, 2),
                'status' => 'pending',
                'shipping_address' => $shippingAddress,
                'shipping_phone' => $shippingPhone,
            ]);

            foreach ($cart->cartItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => (float) $item->product->price,
                ]);
                $item->product->decrement('stock_quantity', $item->quantity);
            }

            $cart->cartItems()->delete();

            return $order;
        });

        $order->load('orderItems.product');

        return $this->success(new OrderResource($order), 'Commande créée.', 201);
    }

    #[OA\Post(
        path: '/orders/{id}/cancel',
        summary: 'Annuler une commande',
        description: 'Annule une commande en statut « pending » uniquement. Le stock des produits est recrédité.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Commande annulée (stock recrédité)', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', ref: '#/components/schemas/OrderResponse'),
            ])),
            new OA\Response(response: 403, description: 'Non autorisé'),
            new OA\Response(response: 404, description: 'Commande non trouvée'),
            new OA\Response(response: 422, description: 'Commande non annulable (déjà payée, expédiée ou annulée)'),
        ]
    )]
    public function cancel(Request $request, Order $order): JsonResponse
    {
        if ((string) $order->user_id !== (string) $request->user()->id) {
            return $this->error('Non autorisé.', 403);
        }

        if ($order->status !== 'pending') {
            return $this->error('Seules les commandes en attente peuvent être annulées.', 422);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            $order->load('orderItems');
            foreach ($order->orderItems as $item) {
                Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            }
            $order->update(['status' => 'cancelled']);
        });

        $order->load('orderItems.product');

        return $this->success(new OrderResource($order), 'Commande annulée.', 200);
    }

    #[OA\Post(
        path: '/orders/{id}/payment',
        summary: 'Initier le paiement FedaPay',
        description: 'Pour une commande en statut pending, crée une transaction FedaPay (compte test) et retourne l\'URL de paiement. L\'app ouvre cette URL (WebView/navigateur) ; après paiement, FedaPay envoie un webhook et la commande passe en paid.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'success', type: 'boolean', example: true),
                new OA\Property(property: 'message', type: 'string'),
                new OA\Property(property: 'data', properties: [
                    new OA\Property(property: 'payment_url', type: 'string', description: 'URL à ouvrir pour payer'),
                    new OA\Property(property: 'token', type: 'string'),
                    new OA\Property(property: 'transaction_id', type: 'string'),
                ], type: 'object'),
            ])),
            new OA\Response(response: 403, description: 'Non autorisé'),
            new OA\Response(response: 404, description: 'Commande non trouvée'),
            new OA\Response(response: 422, description: 'Commande déjà payée ou non pending / FedaPay non configuré'),
        ]
    )]
    public function initiatePayment(Request $request, Order $order): JsonResponse
    {
        if ((string) $order->user_id !== (string) $request->user()->id) {
            return $this->error('Non autorisé.', 403);
        }

        if ($order->status !== 'pending') {
            return $this->error('Seules les commandes en attente peuvent être payées.', 422);
        }

        $fedapay = new FedaPayService;
        $payment = $fedapay->createPaymentForOrder($order);

        if (! $payment) {
            return $this->error('Impossible d\'initier le paiement FedaPay. Vérifiez la configuration (FEDAPAY_SECRET_KEY).', 422);
        }

        return $this->success($payment, 'URL de paiement générée. Ouvrez payment_url dans un navigateur ou WebView.', 200);
    }
}
