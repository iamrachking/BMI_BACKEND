@extends('gestion.layouts.dashboard')

@section('title', 'Utilisateurs')
@section('header', 'Utilisateurs')

@section('content')
<div class="space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <form action="{{ route('users.index') }}" method="get" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom et prénom ou email…" class="rounded-lg border-gray-300 text-sm w-48">
            <select name="role" class="rounded-lg border-gray-300 text-sm">
                <option value="">Tous les rôles</option>
                @foreach($roles as $r)
                    <option value="{{ $r->name }}" {{ request('role') === $r->name ? 'selected' : '' }}>{{ ucfirst($r->name) }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">Filtrer</button>
        </form>
        <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">
            <i class="fas fa-user-plus"></i> Créer un utilisateur
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom et prénom</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($users as $u)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $u->name }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $u->email }}</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst($u->role->name) }}</span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('users.edit', $u) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">Modifier</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-10 text-center text-gray-500">Aucun utilisateur.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-3 border-t border-gray-200">{{ $users->links() }}</div>
        @endif
    </div>
</div>
@endsection
