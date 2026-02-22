@extends('gestion.layouts.dashboard')

@section('title', 'Catégories')
@section('header', 'Catégories')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form action="{{ route('admin.categories.index') }}" method="get" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher…" class="rounded-lg border-gray-300 text-sm w-48">
            <button type="submit" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200"><i class="fas fa-search mr-1"></i> Filtrer</button>
        </form>
        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">
            <i class="fas fa-plus"></i> Nouvelle catégorie
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Image</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Produits</th>
                    <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-28">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse($categories as $category)
                    <tr class="hover:bg-gray-50/80">
                        <td class="px-5 py-3 align-middle">
                            <div class="flex-shrink-0 w-14 h-14 rounded-lg overflow-hidden border border-gray-200 bg-gray-100">
                                @if($category->imageUrl())
                                    <img src="{{ $category->imageUrl() }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fas fa-image text-xl"></i></div>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3 align-middle">
                            <a href="{{ route('admin.categories.show', $category) }}" class="font-semibold text-gray-900 hover:text-indigo-600">{{ $category->name }}</a>
                        </td>
                        <td class="px-5 py-3 align-middle text-sm text-gray-500 max-w-xs">
                            <span class="line-clamp-2">{{ Str::limit($category->description, 80) ?: '—' }}</span>
                        </td>
                        <td class="px-5 py-3 align-middle text-sm text-gray-600">{{ $category->products_count }} produit(s)</td>
                        <td class="px-5 py-3 text-right align-middle">
                            <div class="inline-flex items-center justify-end gap-1">
                                <a href="{{ route('admin.categories.show', $category) }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700" title="Voir"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="p-2 rounded-lg text-indigo-600 hover:bg-indigo-50" title="Modifier"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="post" class="delete-form inline" data-title="Supprimer cette catégorie ?">@csrf @method('delete')
                                    <button type="submit" class="p-2 rounded-lg text-red-600 hover:bg-red-50" title="Supprimer"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-16 text-center text-gray-500">Aucune catégorie. <a href="{{ route('admin.categories.create') }}" class="text-indigo-600 hover:underline font-medium">Créer la première</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())<div class="mt-4">{{ $categories->links() }}</div>@endif
</div>
@endsection
