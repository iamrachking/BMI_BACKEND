@extends('gestion.layouts.dashboard')

@section('title', 'Planifier une maintenance')
@section('header', 'Planifier une maintenance')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('maintenances.store') }}" method="post" class="space-y-5 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label for="equipment_id" class="block text-sm font-medium text-gray-700">Équipement *</label>
                <select name="equipment_id" id="equipment_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Choisir…</option>
                    @foreach($equipments as $eq)
                        <option value="{{ $eq->id }}" {{ old('equipment_id', request('equipment_id')) == $eq->id ? 'selected' : '' }}>{{ $eq->name }} ({{ $eq->reference ?? $eq->id }})</option>
                    @endforeach
                </select>
                @error('equipment_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="user_id" class="block text-sm font-medium text-gray-700">Technicien *</label>
                <select name="user_id" id="user_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Choisir…</option>
                    @foreach($techniciens as $t)
                        <option value="{{ $t->id }}" {{ old('user_id') == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->email }})</option>
                    @endforeach
                </select>
                @error('user_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Type *</label>
                <select name="type" id="type" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="preventive" {{ old('type') === 'preventive' ? 'selected' : '' }}>Préventive</option>
                    <option value="corrective" {{ old('type') === 'corrective' ? 'selected' : '' }}>Corrective</option>
                </select>
                @error('type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="scheduled_date" class="block text-sm font-medium text-gray-700">Date prévue *</label>
                <input type="date" name="scheduled_date" id="scheduled_date" value="{{ old('scheduled_date') }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('scheduled_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Statut *</label>
                <select name="status" id="status" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="planifie" {{ old('status', 'planifie') === 'planifie' ? 'selected' : '' }}>Planifié</option>
                    <option value="en_cours" {{ old('status') === 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="termine" {{ old('status') === 'termine' ? 'selected' : '' }}>Terminé</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">Enregistrer</button>
            <a href="{{ route('maintenances.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Annuler</a>
        </div>
    </form>
</div>
@endsection
