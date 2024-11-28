@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold text-pink-900 my-4">Types de produits</h1>
            @include('shared.link-button', [
                'text' => 'Créer un type de produit',
                'href' => route('admin.productType.create'),
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
                'production_speed' => 'Vitesse de production',
            ],
            'rows' => $productTypes->map(function ($type) {
                return [
                    'id' => $type->id,
                    'name' => $type->name,
                    'production_speed' => $type->production_speed . ' unités/heure',
                ];
            }),
            'actions' => function ($row) {
                return view('shared.actions', [
                    'edit_url' => route('admin.productType.edit', $row['id']),
                    'delete_url' => route('admin.productType.destroy', $row['id']),
                ]);
            },
        ])
    </div>
@endsection
