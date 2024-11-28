<div class="flex flex-col h-full p-6 fixed z-50">
    <div class="flex justify-center items-center mb-6">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 h-24 object-contain">
    </div>

    <ul class="space-y-2">
        <li>
            <a href="{{ route('admin.home.index') }}"
                class="block px-3 py-2 rounded {{ request()->routeIs('admin.home.index') ? 'bg-pink-600 text-pink-200 font-bold' : 'text-gray-700 hover:bg-pink-600 hover:text-pink-200' }}">
                Accueil
            </a>
        </li>
        <li>
            <a href="{{ route('admin.order.index') }}"
                class="block px-3 py-2 rounded {{ request()->routeIs('admin.order.index') || request()->routeIs('admin.order.create') ? 'bg-pink-600 text-pink-200 font-bold' : 'text-gray-700 hover:bg-pink-600 hover:text-pink-200' }}">
                Commandes
            </a>
        </li>
        <li>
            <a href="{{ route('admin.client.index') }}"
                class="block px-3 py-2 rounded {{ request()->routeIs('admin.client.index') || request()->routeIs('admin.client.create') ? 'bg-pink-600 text-pink-200 font-bold' : 'text-gray-700 hover:bg-pink-600 hover:text-pink-200' }}">
                Clients
            </a>
        </li>
        <li>
            <a href="{{ route('admin.productType.index') }}"
                class="block px-3 py-2 rounded {{ request()->routeIs('admin.productType.index') || request()->routeIs('admin.productType.create') ? 'bg-pink-600 text-pink-200 font-bold' : 'text-gray-700 hover:bg-pink-600 hover:text-pink-200' }}">
                Types de produit
            </a>
        </li>
        <li>
            <a href="{{ route('admin.product.index') }}"
                class="block px-3 py-2 rounded {{ request()->routeIs('admin.product.index') || request()->routeIs('admin.product.create') ? 'bg-pink-600 text-pink-200 font-bold' : 'text-gray-700 hover:bg-pink-600 hover:text-pink-200' }}">
                Produits
            </a>

        </li>
    </ul>
</div>
