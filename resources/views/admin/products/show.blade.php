@extends('gestion.layouts.dashboard')

@section('title', $product->name)
@section('header', $product->name)

@section('content')
<div class="space-y-6">
    <div class="flex justify-end gap-1">
        <a href="{{ route('admin.products.edit', $product) }}" class="p-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50" title="Modifier"><i class="fas fa-pen"></i></a>
        <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="delete-form inline" data-title="Supprimer ce produit ?">
            @csrf
            @method('delete')
            <button type="submit" class="p-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50" title="Supprimer"><i class="fas fa-trash"></i></button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Image principale -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm sticky top-24">
                @if($product->imageUrl())
                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="w-full rounded-xl object-cover aspect-square shadow-sm">
                @else
                    <div class="w-full aspect-square rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                        <i class="fas fa-image fa-4x"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Fiche + Description -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="font-semibold text-gray-800 mb-4">Fiche produit</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm text-gray-500">Nom</dt>
                        <dd class="font-medium text-gray-900">{{ $product->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Catégorie</dt>
                        <dd class="font-medium text-gray-900">{{ $product->category->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Prix</dt>
                        <dd class="font-medium text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} FCFA</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Stock disponible</dt>
                        <dd>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium {{ $product->stock_quantity > 5 ? 'bg-emerald-100 text-emerald-800' : ($product->stock_quantity > 0 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>

            @if($product->description)
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-800 mb-3">Description</h3>
                    <div class="text-gray-700 whitespace-pre-wrap">{{ $product->description }}</div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
