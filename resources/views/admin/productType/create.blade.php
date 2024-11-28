@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold my-4 text-pink-900">
                {{ $productType->exists ? 'Modifier le type de produit' : 'Créer un type de produit' }}
            </h1>
            @include('shared.link-button', [
                'text' => 'Retour',
                'href' => route('admin.productType.index'),
                'style' => 'back',
            ])
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex justify-center bg-white p-8 rounded-md">
            <form class="max-w-2xl"
                action="{{ route($productType->exists ? 'admin.productType.update' : 'admin.productType.store', $productType) }}"
                method="POST">
                @csrf
                @if ($productType->exists)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        @include('shared.input', [
                            'name' => 'name',
                            'label' => 'Nom',
                            'value' => old('name', $productType->name),
                        ])
                    </div>

                    <div>
                        @include('shared.input', [
                            'name' => 'production_speed',
                            'label' => 'Vitesse de production (unités/heure)',
                            'value' => old('production_speed', $productType->production_speed),
                            'attributes' => ['step' => 0.01, 'min' => 0],
                        ])
                    </div>
                </div>

                @if ($productTypes->isNotEmpty())
                    <h2 class="text-md font-semibold mt-4 mb-2">Temps de changement</h2>
                    <div class="border border-sm p-4 my-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($productTypes as $type)
                                @if ($type->id !== $productType->id)
                                    <div>
                                        <label for="changeover_time_{{ $type->id }}"
                                            class="block text-sm font-medium text-gray-700">
                                            Vers <strong>{{ $type->name }}</strong> (minutes)
                                        </label>
                                        @include('shared.input', [
                                            'name' => "changeover_times[{$type->id}]",
                                            'type' => 'number',
                                            'value' => old(
                                                'changeover_times.' . $type->id,
                                                $changeoverTimes[$type->id] ?? 0),
                                            'attributes' => ['min' => 0, 'id' => "changeover_time_{$type->id}"],
                                        ])
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="text-right">
                    <button type="submit"
                        class="inline-block px-6 py-2 text-white bg-pink-600 rounded shadow-sm hover:bg-pink-700">
                        {{ $productType->exists ? 'Modifier' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
