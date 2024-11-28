@extends('base')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6 bg-pink-200 px-4 rounded-md">
            <h1 class="text-2xl font-bold my-4 text-pink-900">Créer une commande</h1>
            @include('shared.link-button', [
                'text' => 'Retour',
                'href' => route('admin.order.index'),
                'style' => 'back',
            ])
        </div>
        <div class="flex justify-center bg-white p-8 rounded-md">
            <form class="max-w-2xl w-full" action="{{ route('admin.order.stepper.postStepOne') }}" method="POST"
                id="order-form">
                @csrf
                <div class="flex w-full space-x-6">
                    <div class="mb-6 w-1/2">
                        <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                        <select name="client_id" id="client_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                            <option value="">-- Sélectionnez un client --</option>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6 w-1/2">
                        <label for="deadline" class="block text-sm font-medium text-gray-700 mb-2">Date limite</label>
                        <input type="date" id="deadline" name="deadline" value="{{ old('deadline') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            required>
                        @error('deadline')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <h4 class="text-md font-semibold text-gray-600 bg-gray-200 p-3 rounded-md">Produits
                        disponibles</h4>
                    <div class="flex flex-wrap gap-4 mt-6">
                        @foreach ($products->groupBy('productType.name') as $typeName => $productsByType)
                            <div class="w-full mb-6">
                                <h5 class="text-lg font-semibold text-gray-700">{{ $typeName }}</h5>
                                <div class="grid grid-cols-2 gap-4 mt-4">
                                    @foreach ($productsByType as $product)
                                        <div
                                            class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-lg transition-shadow duration-200">
                                            <div class="p-4">
                                                <div class="flex items-center gap-4">
                                                    <input type="checkbox" id="product_{{ $product->id }}"
                                                        name="items[{{ $product->id }}][product_id]"
                                                        value="{{ $product->id }}"
                                                        class="product-checkbox h-5 w-5 text-blue-600 focus:ring-blue-500 border-gray-300"
                                                        onchange="updateProductInfo({{ $product->id }}, '{{ $typeName }}')"
                                                        data-price="{{ $product->price }}"
                                                        data-type="{{ $typeName }}">

                                                    <div class="flex flex-row items-center gap-4">
                                                        <label for="product_{{ $product->id }}"
                                                            class="font-medium text-gray-800">{{ $product->name }}</label>
                                                        <p class="text-sm text-gray-500">
                                                            {{ number_format($product->price, 2) }} $
                                                        </p>
                                                    </div>
                                                </div>



                                                <div class="flex gap-2">
                                                    <div class="mt-2 hidden" id="quantity-section-{{ $product->id }}">
                                                        <label for="quantity_{{ $product->id }}"
                                                            class="block text-sm font-medium text-gray-700">Quantité</label>
                                                        <input type="number" id="quantity_{{ $product->id }}"
                                                            name="items[{{ $product->id }}][quantity]"
                                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                            value="100" min="1"
                                                            onchange="updateProductInfo({{ $product->id }}, '{{ $typeName }}')">
                                                    </div>

                                                    <div class="mt-2 hidden" id="total-price-section-{{ $product->id }}">
                                                        <label class="block text-sm text-gray-700">Prix total</label>
                                                        <input type="text" id="total_price_{{ $product->id }}"
                                                            class="mt-1 block w-full rounded-md bg-gray-100 text-gray-500"
                                                            value="{{ number_format($product->price, 2) }}" readonly>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="px-6 py-2 bg-pink-600 hover-pink-700 text-white rounded-lg shadow-md">
                        Suivant
                    </button>
                </div>
        </div>

        <input type="hidden" name="product_data" id="product_data">
        </form>
    </div>



    <script>
        document.getElementById('deadline').setAttribute('min', new Date().toISOString().split('T')[0]);
        // Fonction qui met à jour le prix total, l'affichage des sections et la sélection des produits
        function updateProductInfo(productId, productType) {
            const checkbox = document.getElementById(`product_${productId}`);
            const price = parseFloat(checkbox.getAttribute('data-price'));
            const quantitySection = document.getElementById(`quantity-section-${productId}`);
            const totalPriceSection = document.getElementById(`total-price-section-${productId}`);
            const quantityInput = document.getElementById(`quantity_${productId}`);
            const totalPriceField = document.getElementById(`total_price_${productId}`);

            if (checkbox.checked) {
                // Désactiver les checkboxes des autres types de produits
                document.querySelectorAll('.product-checkbox').forEach(cb => {
                    if (cb.getAttribute('data-type') !== productType) {
                        cb.disabled = true;
                    }
                });

                quantitySection.classList.remove('hidden');
                totalPriceSection.classList.remove('hidden');
                totalPriceField.value = (price * parseInt(quantityInput.value)).toFixed(0); // Conversion en entier

                // Ajouter un événement pour mettre à jour le prix total lorsque la quantité change
                quantityInput.addEventListener('change', () => {
                    totalPriceField.value = (price * parseInt(quantityInput.value)).toFixed(
                        0); // Conversion en entier
                    updateProductData();
                });
            } else {
                // Réactiver les checkboxes des autres types de produits si aucun produit de ce type n'est sélectionné
                if (!document.querySelector(`.product-checkbox[data-type="${productType}"]:checked`)) {
                    document.querySelectorAll('.product-checkbox').forEach(cb => {
                        cb.disabled = false;
                    });
                }

                quantitySection.classList.add('hidden');
                totalPriceSection.classList.add('hidden');
                quantityInput.removeEventListener('change', updateProductData);
            }

            updateProductData();
        }

        function updateProductData() {
            const items = [];
            document.querySelectorAll('input[name^="items["]:checked').forEach(checkbox => {
                const productId = checkbox.value;
                const quantity = document.querySelector(`#quantity_${productId}`).value;
                const totalPrice = parseInt(document.querySelector(`#total_price_${productId}`)
                    .value);
                items.push({
                    product_id: productId,
                    quantity: quantity,
                    total_price: totalPrice
                });
            });

            document.getElementById('product_data').value = JSON.stringify(items);
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('input[name^="items["]').forEach(checkbox => {
                checkbox.addEventListener('change', () => updateProductInfo(checkbox.value, checkbox
                    .getAttribute('data-type')));
                if (checkbox.checked) {
                    updateProductInfo(checkbox.value, checkbox.getAttribute('data-type'));
                }
            });
        });
    </script>
@endsection
