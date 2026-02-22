@extends('gestion.layouts.dashboard')

@section('title', 'Ajouter un équipement')
@section('header', 'Ajouter un équipement')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('equipments.store') }}" method="post" class="space-y-5 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Nom *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="reference" class="block text-sm font-medium text-gray-700">Référence</label>
                <input type="text" name="reference" id="reference" value="{{ old('reference') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('reference')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut *</label>
                <select name="status" id="status" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="panne" {{ old('status') === 'panne' ? 'selected' : '' }}>Panne</option>
                    <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="brand" class="block text-sm font-medium text-gray-700">Marque</label>
                <input type="text" name="brand" id="brand" value="{{ old('brand') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('brand')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="model" class="block text-sm font-medium text-gray-700">Modèle</label>
                <input type="text" name="model" id="model" value="{{ old('model') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('model')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="installation_date" class="block text-sm font-medium text-gray-700">Date d'installation</label>
                <input type="date" name="installation_date" id="installation_date" value="{{ old('installation_date') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('installation_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="location" class="block text-sm font-medium text-gray-700">Emplacement</label>
                <input type="text" name="location" id="location" value="{{ old('location') }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">Enregistrer</button>
            <a href="{{ route('equipments.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Annuler</a>
        </div>
    </form>
</div>
@endsection
