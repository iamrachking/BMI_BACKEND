<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'API AI4BMI',
    description: "API E-commerce pour l'app mobile (clients). Authentification : Bearer token (Sanctum) ; seuls POST /register et POST /login sont publics.\n\n**Cycle commande** : création (POST /orders) → statut `pending` → paiement confirmé (webhook ou back-office) → `paid` → expédition → `shipped`. Annulation possible uniquement si `pending` (POST /orders/{id}/cancel). Adresse de livraison : fournie à la commande ou reprise du profil (PATCH /user)."
)]
#[OA\Server(url: '/api', description: 'API')]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
#[OA\Tag(name: 'Auth', description: 'Inscription, connexion, profil utilisateur (adresse, photo)')]
#[OA\Tag(name: 'E-commerce', description: 'Catalogue, panier, commandes (toutes sous authentification)')]
#[OA\Tag(name: 'Webhooks', description: 'Notification paiement (appelé par prestataire ou back-office)')]
#[OA\Schema(
    schema: 'OrderResponse',
    description: 'Commande avec adresse de livraison et lignes',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'total_amount', type: 'number', format: 'float', example: 99.50),
        new OA\Property(property: 'status', type: 'string', enum: ['pending', 'paid', 'shipped', 'cancelled']),
        new OA\Property(property: 'shipping_address', type: 'string', nullable: true),
        new OA\Property(property: 'shipping_phone', type: 'string', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'items', type: 'array', description: 'Lignes de la commande (product, quantity, price, subtotal)', items: new OA\Items(type: 'object')),
    ]
)]
class OpenApiBase
{
}
