@extends('gestion.layouts.dashboard')

@section('title', 'Boutique – Tableau de bord')
@section('header', 'Boutique – Tableau de bord')

@section('content')
<div class="space-y-6">
    <!-- Cartes stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Catégories</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['categories_count'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                    <i class="fas fa-folder text-indigo-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.categories.index') }}" class="mt-3 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">Gérer <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Produits</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['products_count'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-box text-emerald-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="mt-3 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">Gérer <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Commandes</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['orders_count'] }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $stats['orders_pending'] }} en attente</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-amber-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="mt-3 inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">Voir tout <i class="fas fa-arrow-right ml-1 text-xs"></i></a>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Payées / Expédiées</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['orders_paid'] }} / {{ $stats['orders_shipped'] }}</p>
                </div>
                <div class="h-12 w-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Commandes récentes -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Commandes récentes</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">Voir tout</a>
        </div>
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
                    @forelse($stats['orders_recent'] as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ $order->user->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-sm text-gray-700">{{ number_format($order->total_amount, 0, ',', ' ') }} F</td>
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
                        <tr><td colspan="6" class="px-5 py-8 text-center text-gray-500 text-sm">Aucune commande.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
