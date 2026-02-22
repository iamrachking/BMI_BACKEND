@extends('gestion.layouts.dashboard')

@section('title', 'Modifier l\'utilisateur')
@section('header', 'Modifier l\'utilisateur')

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('users.update', $user) }}" method="post" class="space-y-5 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700">Nom et prénom *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="role_id" class="block text-sm font-medium text-gray-700">Rôle *</label>
                <select name="role_id" id="role_id" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($roles as $r)
                        <option value="{{ $r->id }}" {{ old('role_id', $user->role_id) == $r->id ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                    @endforeach
                </select>
                @error('role_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">Enregistrer</button>
            <a href="{{ route('users.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Annuler</a>
        </div>
    </form>
</div>
@endsection
