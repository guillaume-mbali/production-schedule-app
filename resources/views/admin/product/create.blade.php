@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold my-4 text-pink-900">
                {{ $product->exists ? 'Modifier le produit' : 'Créer un produit' }}</h1>
            @include('shared.link-button', [
                'text' => 'Retour',
                'href' => route('admin.product.index'),
                'style' => 'back',
            ])
        </div>

        <div class="flex justify-center bg-white p-8 rounded-md">
            <form class="max-w-2xl"
                action="{{ route($product->exists ? 'admin.product.update' : 'admin.product.store', $product) }}"
                method="POST">
                @csrf
                @if ($product->exists)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        @include('shared.input', [
                            'label' => 'Nom du produit',
                            'type' => 'text',
                            'name' => 'name',
                            'value' => old('name', $product->name ?? ''),
                            'required' => true,
                        ])
                    </div>

                    <div>
                        @include('shared.input', [
                            'label' => 'Description',
                            'type' => 'textarea',
                            'name' => 'description',
                            'value' => old('description', $product->description ?? ''),
                        ])
                    </div>

                    <div>
                        @include('shared.input', [
                            'label' => 'Prix unitaire',
                            'type' => 'number',
                            'name' => 'price',
                            'value' => old('price', $product->price ?? ''),
                            'required' => true,
                            'attributes' => ['step' => 0.01, 'min' => 0],
                        ])
                    </div>

                    <div>
                        <label for="product_type_id" class="block text-sm font-medium text-gray-700">Type de produit</label>
                        <select name="product_type_id" id="product_type_id"
                            class="block w-full mt-1 rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>
                            <option value="">-- Sélectionnez un type de produit --</option>
                            @foreach ($productTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('product_type_id', $product->product_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_type_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="inline-block px-6 py-2 text-white bg-pink-600 rounded shadow-sm hover:bg-pink-700">
                        {{ $product->exists ? 'Modifier' : 'Créer' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
