@extends('gestion.layouts.dashboard')

@section('title', 'Produits')
@section('header', 'Produits')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form action="{{ route('admin.products.index') }}" method="get" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher…" class="rounded-lg border-gray-300 text-sm w-48">
            <select name="category_id" class="rounded-lg border-gray-300 text-sm">
                <option value="">Toutes les catégories</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200"><i class="fas fa-search mr-1"></i> Filtrer</button>
        </form>
        <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]"><i class="fas fa-plus"></i> Nouveau produit</a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catégorie</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50/80">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-4">
                                <div class="flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                                    @if($product->imageUrl())
                                        <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-box text-xl"></i></div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900">{{ $product->name }}</p>
                                    <p class="text-sm text-gray-500 mt-0.5 line-clamp-2">{{ Str::limit($product->description, 70) ?: '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600">{{ $product->category->name ?? '—' }}</td>
                        <td class="px-5 py-4 text-sm font-medium text-gray-900">{{ number_format($product->price, 0, ',', ' ') }} F</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $product->stock_quantity > 5 ? 'bg-emerald-100 text-emerald-800' : ($product->stock_quantity > 0 ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">{{ $product->stock_quantity }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <div class="inline-flex items-center justify-end gap-1">
                                <a href="{{ route('admin.products.show', $product) }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700" title="Voir"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 rounded-lg text-indigo-600 hover:bg-indigo-50" title="Modifier"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="delete-form inline">@csrf @method('delete')
                                    <button type="submit" class="p-2 rounded-lg text-red-600 hover:bg-red-50" title="Supprimer"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-16 text-center text-gray-500">Aucun produit. <a href="{{ route('admin.products.create') }}" class="text-indigo-600 hover:underline font-medium">Créer le premier</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())<div class="mt-4">{{ $products->links() }}</div>@endif
</div>
@endsection
