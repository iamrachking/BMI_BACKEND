<?php

namespace App\Http\Controllers\Api\Ecommerce;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\CategoryResource;
use App\Models\Ecommerce\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CategoryController extends BaseController
{
    #[OA\Get(
        path: '/categories',
        summary: 'Liste des catégories',
        description: 'Catalogue. Option : with_products=1 pour inclure les produits, per_page. Authentification requise.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(name: 'with_products', in: 'query', required: false, schema: new OA\Schema(type: 'boolean')),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Non authentifié'),
        ]
    )]
    public function index(Request $request): JsonResponse
    {
        $query = Category::query()->withCount('products')->orderBy('name');

        if ($request->boolean('with_products')) {
            $query->with('products');
        }

        $perPage = min((int) $request->get('per_page', 20), 50);
        $categories = $query->paginate($perPage);

        return $this->success(CategoryResource::collection($categories), 'OK', 200);
    }

    #[OA\Get(
        path: '/categories/{id}',
        summary: 'Détail d\'une catégorie',
        description: 'Catégorie avec liste des produits. Authentification requise.',
        tags: ['E-commerce'],
        security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 200, description: 'OK'),
            new OA\Response(response: 401, description: 'Non authentifié'),
            new OA\Response(response: 404, description: 'Non trouvé'),
        ]
    )]
    public function show(Category $category): JsonResponse
    {
        $category->load('products');

        return $this->success(new CategoryResource($category), 'OK', 200);
    }
}
