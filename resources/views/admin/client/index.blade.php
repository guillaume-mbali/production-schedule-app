@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold text-pink-900 my-4">Liste des clients</h1>
            @include('shared.link-button', [
                'text' => 'Créer un Client',
                'href' => route('admin.client.create'),
                'style' => 'primary',
            ])
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @include('shared.table', [
            'headers' => [
                'name' => 'Nom',
                'address' => 'Adresse',
                'email' => 'Email',
                'phone_number' => 'Téléphone',
            ],
            'rows' => $clients->map(function ($client) {
                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'address' => "{$client->address}, {$client->postal_code} {$client->city}, {$client->country}",
                    'email' => $client->email,
                    'phone_number' => $client->phone_number,
                ];
            }),
            'actions' => function ($row) {
                return view('shared.actions', [
                    'edit_url' => route('admin.client.edit', $row['id']),
                    'delete_url' => route('admin.client.destroy', $row['id']),
                ]);
            },
        ])
    </div>
@endsection
