@php
    $edit_url ??= '#';
    $delete_url ??= '#';
@endphp

<div class="flex items-center space-x-4">
    @include('shared.link-button', [
        'text' => 'Modifier',
        'href' => $edit_url,
        'style' => 'secondary',
        'class' => 'text-sm',
    ])

    <form action="{{ $delete_url }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="submit"
            class="inline-block px-4 py-2 text-sm font-medium text-red-600 border border-red-600 rounded focus:outline-none hover:bg-red-700 hover:text-white focus:ring focus:ring-red-300"
            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')">
            Supprimer
        </button>
    </form>
</div>
