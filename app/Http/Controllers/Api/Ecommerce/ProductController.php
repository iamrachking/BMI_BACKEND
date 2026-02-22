<?php

namespace App\Http\Controllers\Api\Ecommerce;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\ProductResource;
use App\Models\Ecommerce\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProductController extends BaseController
{
    #[OA\Get(
        path: '/products',
        summary: 'Liste des produits',
        description: 'Catalogue. Filtres : category_id, search, per_page. Authentification requise.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'category_id', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'search', in: 'query', required: false, schema: new OA\Schema(type: 'string')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('description', 'like', "%{$s}%"));
        }

        $query->orderBy('name');
        $perPage = min((int) $request->get('per_page', 20), 50);
        $products = $query->paginate($perPage);

        return $this->success(ProductResource::collection($products), 'OK', 200);
    }

    #[OA\Get(
        path: '/products/{id}',
        summary: 'Détail d\'un produit',
        description: 'Authentification requise.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Non trouvé'),
        ]
    )]
    public function show(Product $product): JsonResponse
    {
        $product->load('category');

        return $this->success(new ProductResource($product), 'OK', 200);
    }
}
