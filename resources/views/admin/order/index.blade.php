@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold text-pink-900 my-4">Liste des commandes</h1>
            @include('shared.link-button', [
                'text' => 'Créer une commande',
                'href' => route('admin.order.create'),
                'style' => 'primary',
            ])
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @include('shared.table', [
            'headers' => [
                'created_at' => 'Date de création',
                'deadline' => 'Date limite',
                'client_name' => 'Nom du client',
                'product_type' => 'Type de produit',
                'product_count' => 'Produits(s)',
                'status' => 'Statut',
            ],
            'rows' => $orders->map(function ($order) {
                // Get the product type of the first item in the order (if available)
                $productType = $order->orderItems->first()->product->productType->name ?? 'Non défini';
        
                // Retrieve the last production schedule from the order items
                $lastProduction = $order->orderItems->flatMap(function ($orderItem) {
                        return $orderItem->productionSchedules;
                    })->sortByDesc('start_time')->first();
        
                // Format the last production date
                $lastProductionDate = $lastProduction
                    ? \Carbon\Carbon::parse($lastProduction->end_time)->format('d/m/Y H:i')
                    : 'Non définie';
        
                // Get the product names for this order
                $productNames = $order->orderItems->map(function ($orderItem) {
                    return $orderItem->product->name;
                });
        
                // Convert the product names array into a comma-separated string
                $productNamesString = implode(', ', $productNames->toArray());
        
                // Return an array with all necessary data for the table row
                return [
                    'id' => $order->id,
                    'created_at' => \Carbon\Carbon::parse($order->created_at)->format('d/m/Y'),
                    'deadline' => \Carbon\Carbon::parse($order->deadline)->format('d/m/Y'),
                    'client_name' => $order->client->name,
                    'product_type' => $productType,
                    'product_count' => '[' . $productNamesString . ']',
                    'last_production_date' => $lastProductionDate,
                    'status' => config('statuses.order')[$order->status] ?? 'Statut inconnu',
                ];
            }),
            'actions' => function ($row) {
                return view('shared.actions', [
                    'edit_url' => route('admin.order.edit', $row['id']),
                    'delete_url' => route('admin.order.destroy', $row['id']),
                ]);
            },
        ])
    </div>
@endsection
