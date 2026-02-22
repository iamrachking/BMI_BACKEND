@extends('gestion.layouts.dashboard')

@section('title', 'Déclarer une panne')
@section('header', 'Déclarer une panne')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('failures.store') }}" method="post" class="space-y-5 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
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
            <div>
                <label for="detected_at" class="block text-sm font-medium text-gray-700">Date et heure *</label>
                <input type="datetime-local" name="detected_at" id="detected_at" value="{{ old('detected_at', now()->format('Y-m-d\TH:i')) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('detected_at')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="severity" class="block text-sm font-medium text-gray-700">Gravité *</label>
                <select name="severity" id="severity" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="faible" {{ old('severity') === 'faible' ? 'selected' : '' }}>Faible</option>
                    <option value="moyen" {{ old('severity') === 'moyen' ? 'selected' : '' }}>Moyen</option>
                    <option value="critique" {{ old('severity') === 'critique' ? 'selected' : '' }}>Critique</option>
                </select>
                @error('severity')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assigner à un technicien (optionnel)</label>
                <select name="assigned_to" id="assigned_to" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">— Aucun —</option>
                    @foreach($techniciens as $t)
                        <option value="{{ $t->id }}" {{ old('assigned_to') == $t->id ? 'selected' : '' }}>{{ $t->name }} ({{ $t->email }})</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Le technicien recevra un email s'il est assigné.</p>
                @error('assigned_to')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">Enregistrer</button>
            <a href="{{ route('failures.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Annuler</a>
        </div>
    </form>
</div>
@endsection
