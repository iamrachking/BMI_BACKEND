@extends('gestion.layouts.dashboard')

@section('title', $category->name)
@section('header', $category->name)

@section('content')
<div class="space-y-6">
    <div class="flex justify-end gap-1">
        <a href="{{ route('admin.categories.edit', $category) }}" class="p-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50" title="Modifier"><i class="fas fa-pen"></i></a>
        <form action="{{ route('admin.categories.destroy', $category) }}" method="post" class="delete-form inline" data-title="Supprimer cette catégorie ?">
            @csrf
            @method('delete')
            <button type="submit" class="p-2 rounded-lg border border-red-200 text-red-600 hover:bg-red-50" title="Supprimer"><i class="fas fa-trash"></i></button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                @if($category->imageUrl())
                    <img src="{{ $category->imageUrl() }}" alt="{{ $category->name }}" class="w-full rounded-xl object-cover aspect-square shadow-sm">
                @else
                    <div class="w-full aspect-square rounded-lg bg-gray-100 flex items-center justify-center text-gray-400"><i class="fas fa-image fa-4x"></i></div>
                @endif
            </div>
        </div>
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                <h3 class="font-semibold text-gray-800 mb-4">Informations</h3>
                <dl class="space-y-3">
                    <div><dt class="text-sm text-gray-500">Nom</dt><dd class="font-medium text-gray-900">{{ $category->name }}</dd></div>
                    <div><dt class="text-sm text-gray-500">Nombre de produits</dt><dd class="font-medium text-gray-900">{{ $category->products_count }}</dd></div>
                </dl>
            </div>
            @if($category->description)
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-800 mb-3">Description</h3>
                    <div class="text-gray-700 whitespace-pre-wrap">{{ $category->description }}</div>
                </div>
            @endif
            @if($category->products->isNotEmpty())
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Produits de cette catégorie</h3>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @foreach($category->products as $p)
                            <li class="px-5 py-3 flex items-center justify-between hover:bg-gray-50">
                                <span class="font-medium text-gray-900">{{ $p->name }}</span>
                                <a href="{{ route('admin.products.show', $p) }}" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100" title="Voir"><i class="fas fa-eye"></i></a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
