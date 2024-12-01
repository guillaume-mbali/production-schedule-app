@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold my-4 text-pink-900">Modifier la commande</h1>
            @include('shared.link-button', [
                'text' => 'Retour',
                'href' => route('admin.order.index'),
                'style' => 'back',
            ])
        </div>

        <div class="flex justify-center bg-white p-8 rounded-md">
            <form class="max-w-2xl w-full" action="{{ route('admin.order.update', $order->id) }}" method="POST"
                class="space-y-6">
                @csrf
                @method('PUT')

                <div class="flex gap-6 mb-6">
                    <div class="flex-1">
                        <label for="client_name" class="block text-sm font-medium text-gray-700">Client</label>
                        {{ $order->client->name }}
                    </div>

                    <div class="flex-1">
                        <label for="deadline" class="block text-sm font-medium text-gray-700">Date limite</label>
                        <input type="date" name="deadline" id="deadline" value="{{ $order->deadline->format('Y-m-d') }}"
                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                    </div>

                    <div class="flex-1">
                        <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                        <select id="status" name="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm  sm:text-sm">
                            @foreach (config('statuses.order') as $statusKey => $statusLabel)
                                <option value="{{ $statusKey }}" {{ $statusKey == $order->status ? 'selected' : '' }}>
                                    {{ $statusLabel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="products" class="block text-md font-semibold text-gray-700 my-4">Détail</label>
                    @include('shared.table', [
                        'headers' => [
                            'product_name' => ['label' => 'Produit'],
                            'quantity' => ['label' => 'Quantité'],
                            'production_duration' => ['label' => 'Durée de Production'],
                        ],
                        'rows' => $orderItemsWithDurations,
                    ])
                </div>

                <div class="text-right">
                    <button type="submit" class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg">Mettre
                        à jour</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('deadline');
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
        });
    </script>
@endsection
