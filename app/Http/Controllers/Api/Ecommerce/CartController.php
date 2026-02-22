<?php

namespace App\Http\Controllers\Api\Ecommerce;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\CartResource;
use App\Models\Ecommerce\Cart;
use App\Models\Ecommerce\CartItem;
use App\Models\Ecommerce\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CartController extends BaseController
{
    #[OA\Get(
        path: '/cart',
        summary: 'Voir le panier',
        description: 'Panier de l\'utilisateur (créé si vide). Lignes avec product et quantity.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    public function show(Request $request): JsonResponse
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['user_id' => $request->user()->id]
        );
        $cart->load(['cartItems.product']);

        return $this->success(new CartResource($cart), 'OK', 200);
    }

    #[OA\Delete(
        path: '/cart',
        summary: 'Vider le panier',
        description: 'Supprime toutes les lignes du panier.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Panier vidé'),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    public function clear(Request $request): JsonResponse
    {
        $cart = Cart::where('user_id', $request->user()->id)->first();
        if ($cart) {
            $cart->cartItems()->delete();
            $cart->load(['cartItems.product']);
        } else {
            $cart = Cart::firstOrCreate(
                ['user_id' => $request->user()->id],
                ['user_id' => $request->user()->id]
            );
        }
        return $this->success(new CartResource($cart), 'Panier vidé.', 200);
    }

    #[OA\Post(
        path: '/cart/items',
        summary: 'Ajouter au panier',
        description: 'Ajoute un produit ou augmente la quantité. Vérification du stock.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['product_id', 'quantity'],
                properties: [
                    new OA\Property(property: 'product_id', type: 'integer'),
                    new OA\Property(property: 'quantity', type: 'integer', minimum: 1),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Article ajouté'),
            new OA\Response(response: 422, description: 'Stock insuffisant'),
        ]
    )]
    public function addItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        if ($product->stock_quantity < $validated['quantity']) {
            return $this->error('Stock insuffisant pour ce produit.', 422);
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['user_id' => $request->user()->id]
        );

        $item = $cart->cartItems()->where('product_id', $product->id)->first();
        if ($item) {
            $newQty = $item->quantity + $validated['quantity'];
            if ($product->stock_quantity < $newQty) {
                return $this->error('Stock insuffisant pour la quantité demandée.', 422);
            }
            $item->update(['quantity' => $newQty]);
        } else {
            $cart->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        $cart->load(['cartItems.product']);

        return $this->success(new CartResource($cart), 'Article ajouté au panier.', 200);
    }

    #[OA\Patch(
        path: '/cart/items/{cartItem}',
        summary: 'Modifier une ligne du panier',
        description: 'Mettre à jour la quantité. Si quantity=0, la ligne est supprimée.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'cartItem', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(required: ['quantity'], properties: [
                new OA\Property(property: 'quantity', type: 'integer', minimum: 0),
            ])
        ),
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 403, description: 'Non autorisé'),
        ]
    )]
    public function updateItem(Request $request, CartItem $cartItem): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $cart = Cart::where('user_id', $request->user()->id)->firstOrFail();
        if ((string) $cartItem->cart_id !== (string) $cart->id) {
            return $this->error('Non autorisé.', 403);
        }

        if ($validated['quantity'] === 0) {
            $cartItem->delete();
            $cart->load(['cartItems.product']);
            return $this->success(new CartResource($cart), 'Ligne supprimée du panier.', 200);
        }

        $product = $cartItem->product;
        if ($product->stock_quantity < $validated['quantity']) {
            return $this->error('Stock insuffisant.', 422);
        }
        $cartItem->update(['quantity' => $validated['quantity']]);
        $cart->load(['cartItems.product']);

        return $this->success(new CartResource($cart), 'Panier mis à jour.', 200);
    }

    #[OA\Delete(
        path: '/cart/items/{cartItem}',
        summary: 'Supprimer une ligne du panier',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'cartItem', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'Ligne supprimée'),
            new OA\Response(response: 403, description: 'Non autorisé'),
        ]
    )]
    public function removeItem(Request $request, CartItem $cartItem): JsonResponse
    {
        $cart = Cart::where('user_id', $request->user()->id)->firstOrFail();
        if ((string) $cartItem->cart_id !== (string) $cart->id) {
            return $this->error('Non autorisé.', 403);
        }
        $cartItem->delete();
        $cart->load(['cartItems.product']);

        return $this->success(new CartResource($cart), 'Ligne supprimée du panier.', 200);
    }
}
