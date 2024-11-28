@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold my-4 text-pink-900">Valider la commande</h1>
            @include('shared.link-button', [
                'text' => 'Retour',
                'href' => route('admin.order.stepper.step1'),
                'style' => 'back',
            ])
        </div>
        <div class="flex justify-center bg-white p-8 rounded-md">
            <form class="max-w-2xl w-full" action="{{ route('admin.order.stepper.postStepTwo') }}" method="POST">
                @csrf

                <div class="flex items-center justify-between">
                    <div class="flex gap-1">
                        <p class="text-md font-semibold">Client :</p>
                        <span>{{ $order->client->name }}</span>
                    </div>
                    <div class="flex gap-1">
                        <p class="text-md font-semibold">Date limite :</p>
                        <span> {{ $order->deadline->format('d/m/Y') }}</span>
                    </div>
                </div>

                <div class="my-8">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4">Produits sélectionnés</h4>

                    @include('shared.table', [
                        'headers' => [
                            'product_name' => ['label' => 'Nom du produit'],
                            'quantity' => ['label' => 'Quantité'],
                            'price' => ['label' => 'Prix total'],
                        ],
                        'rows' => $products->map(function ($product) use ($orderItems) {
                            $orderItem = $orderItems->firstWhere('product_id', $product->id);
                    
                            return [
                                'product_name' => $product->name,
                                'quantity' => $orderItem->quantity,
                                'price' => number_format($orderItem->price, 2, ',', ' ') . ' $',
                            ];
                        }),
                    ])
                </div>
                <div class="text-right">
                    <button type="submit"
                        class="px-6 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg">Valider</button>
                </div>
            </form>
        </div>
    </div>
@endsection
