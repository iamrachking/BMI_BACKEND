@extends('gestion.layouts.dashboard')

@section('title', 'Nouvelle catégorie')
@section('header', 'Nouvelle catégorie')

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Colonne gauche : Image -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Image</h3>
                    <input type="file" name="image" id="image" accept=".png,.jpg,.jpeg" class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200">
                    <p class="mt-2 text-xs text-gray-500">PNG, JPG ou JPEG. Max 2 Mo.</p>
                    @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Colonne droite : General (nom, description) -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <h3 class="text-sm font-semibold text-gray-800 mb-4">Général</h3>

                    <div class="mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom de la catégorie *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Nom de la catégorie" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-gray-500">Un nom est requis et doit être unique.</p>
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="5" placeholder="Description de la catégorie…" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">Enregistrer</button>
            <a href="{{ route('admin.categories.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Annuler</a>
        </div>
    </form>
</div>

@endsection
