@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold my-4 text-pink-900">
                {{ $client->exists ? 'Modifier le client' : 'Créer un client' }}
            </h1>
            @include('shared.link-button', [
                'text' => 'Retour',
                'href' => route('admin.client.index'),
                'style' => 'back',
            ])
        </div>

        <div class="flex justify-center bg-white p-8 rounded-md">
            <form class="w-full max-w-2xl"
                action="{{ route($client->exists ? 'admin.client.update' : 'admin.client.store', $client) }}" method="POST">
                @csrf
                @if ($client->exists)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        @include('shared.input', [
                            'label' => 'Nom',
                            'type' => 'text',
                            'name' => 'name',
                            'value' => old('name', $client->name ?? ''),
                            'required' => true,
                        ])
                    </div>

                    <div>
                        @include('shared.input', [
                            'label' => 'Adresse',
                            'type' => 'text',
                            'name' => 'address',
                            'value' => old('address', $client->address ?? ''),
                            'required' => true,
                        ])
                    </div>

                    <div>
                        @include('shared.input', [
                            'label' => 'Code Postal',
                            'type' => 'text',
                            'name' => 'postal_code',
                            'value' => old('postal_code', $client->postal_code ?? ''),
                            'required' => true,
                        ])
                    </div>

                    <div>
                        @include('shared.input', [
                            'label' => 'Ville',
                            'type' => 'text',
                            'name' => 'city',
                            'value' => old('city', $client->city ?? ''),
                            'required' => true,
                        ])
                    </div>

                    <div>
                        @include('shared.input', [
                            'label' => 'Pays',
                            'type' => 'text',
                            'name' => 'country',
                            'value' => old('country', $client->country ?? ''),
                            'required' => true,
                        ])
                    </div>

                    <div>
                        @include('shared.input', [
                            'label' => 'Email',
                            'type' => 'email',
                            'name' => 'email',
                            'value' => old('email', $client->email ?? ''),
                            'required' => true,
                        ])
                    </div>

                    <div>
                        @include('shared.input', [
                            'label' => 'Numéro de téléphone',
                            'type' => 'text',
                            'name' => 'phone_number',
                            'value' => old('phone_number', $client->phone_number ?? ''),
                            'required' => true,
                        ])
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="inline-block px-6 py-2 text-white bg-pink-600 rounded shadow-sm hover:bg-pink-700">
                        {{ $client->exists ? 'Modifier le client' : 'Créer le client' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
