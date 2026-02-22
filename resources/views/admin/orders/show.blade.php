@extends('gestion.layouts.dashboard')

@section('title', 'Commande #' . $order->id)
@section('header', 'Commande #' . $order->id)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
                @if($order->status === 'pending') bg-amber-100 text-amber-800
                @elseif($order->status === 'paid') bg-emerald-100 text-emerald-800
                @elseif($order->status === 'shipped') bg-indigo-100 text-indigo-800
                @else bg-gray-100 text-gray-600 @endif">
                {{ $order->status }}
            </span>
            <span class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-1"></i> Retour
            </a>
            @if(in_array($order->status, ['pending', 'paid']))
                <form action="{{ route('admin.orders.update-status', $order) }}" method="post" class="inline">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="status" value="{{ $order->status === 'pending' ? 'paid' : 'shipped' }}">
                    <button type="submit" class="rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">
                        @if($order->status === 'pending')
                            <i class="fas fa-check mr-1"></i> Marquer payée
                        @else
                            <i class="fas fa-truck mr-1"></i> Marquer expédiée
                        @endif
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Client & livraison -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-4">
            <h3 class="font-semibold text-gray-800">Client</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">Nom</dt><dd class="font-medium text-gray-900">{{ $order->user->name ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Email</dt><dd class="text-gray-900">{{ $order->user->email ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Téléphone livraison</dt><dd class="text-gray-900">{{ $order->shipping_phone ?? $order->user->phone ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Adresse de livraison</dt><dd class="text-gray-900">{{ $order->shipping_address ?? $order->user->address ?? '—' }}</dd></div>
            </dl>
        </div>

        <!-- Changer le statut (select manuel) -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-4">Changer le statut</h3>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="post" class="flex flex-wrap items-end gap-3">
                @csrf
                @method('patch')
                <div class="flex-1 min-w-[140px]">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" id="status" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Payée</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Expédiée</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <button type="submit" class="rounded-lg bg-[#2e4053] px-4 py-2 text-sm font-medium text-white hover:bg-[#243447]">Mettre à jour</button>
            </form>
        </div>
    </div>

    <!-- Lignes de la commande -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Articles ({{ $order->orderItems->count() }})</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Prix unit.</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qté</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-gray-500 uppercase">Sous-total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $item->product->name ?? 'Produit #' . $item->product_id }}</td>
                            <td class="px-5 py-3 text-right text-sm text-gray-700">{{ number_format($item->price, 0, ',', ' ') }} F</td>
                            <td class="px-5 py-3 text-right text-sm text-gray-700">{{ $item->quantity }}</td>
                            <td class="px-5 py-3 text-right text-sm font-medium text-gray-900">{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} F</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="3" class="px-5 py-3 text-right font-medium text-gray-700">Total</td>
                        <td class="px-5 py-3 text-right font-bold text-gray-900">{{ number_format($order->total_amount, 0, ',', ' ') }} F</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
