@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold text-pink-900 my-4">Produits</h1>
            @include('shared.link-button', [
                'text' => 'Créer un Produit',
                'href' => route('admin.product.create'),
                'style' => 'primary',
            ])
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @include('shared.table', [
            'headers' => [
                'name' => 'Nom',
                'description' => 'Description',
                'price' => 'Prix ($)',
            ],
            'rows' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => number_format($product->price, 2) . ' $',
                ];
            }),
            'actions' => function ($row) {
                return view('shared.actions', [
                    'edit_url' => route('admin.product.edit', $row['id']),
                    'delete_url' => route('admin.product.destroy', $row['id']),
                ]);
            },
        ])
    </div>
@endsection
