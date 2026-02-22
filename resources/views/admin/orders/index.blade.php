@extends('gestion.layouts.dashboard')

@section('title', 'Commandes')
@section('header', 'Commandes')

@section('content')
<div class="space-y-4">
    <form action="{{ route('admin.orders.index') }}" method="get" class="flex flex-wrap items-center gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="N° commande ou client…" class="rounded-lg border-gray-300 text-sm w-48">
        <select name="status" class="rounded-lg border-gray-300 text-sm">
            <option value="">Tous les statuts</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Payée</option>
            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Expédiée</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulée</option>
        </select>
        <button type="submit" class="rounded-lg bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200"><i class="fas fa-search mr-1"></i> Filtrer</button>
    </form>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">N°</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $order->user->name ?? '—' }}<br><span class="text-gray-500 text-xs">{{ $order->user->email ?? '' }}</span></td>
                            <td class="px-5 py-3 text-sm font-medium text-gray-900">{{ number_format($order->total_amount, 0, ',', ' ') }} F</td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @if($order->status === 'pending') bg-amber-100 text-amber-800
                                    @elseif($order->status === 'paid') bg-emerald-100 text-emerald-800
                                    @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">Détail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-gray-500">Aucune commande.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($orders->hasPages())
        <div class="mt-4">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
