@php
    $text ??= 'Button';
    $href ??= '#';
    $style ??= 'primary';
    $class ??= '';
    $attributes ??= [];

    $baseClass = 'inline-block px-4 py-2 text-sm font-medium rounded focus:outline-none focus:ring';
    $styleClasses = match ($style) {
        'primary' => 'text-white bg-pink-600 hover:bg-pink-700 focus:ring-blue-300',
        'danger' => 'text-red-600 border border-red-600 focus:ring-red-300',
        'secondary' => 'text-gray-600 border border-gray-600 hover:bg-slate-300 hover:text-gray-700',
        default => 'text-white bg-gray-600 hover:bg-gray-700 focus:ring-gray-300',
    };
@endphp

<a href="{{ $href }}" class="{{ $baseClass }} {{ $styleClasses }} {{ $class }}"
    @foreach ($attributes as $attr => $attrValue) {{ $attr }}="{{ $attrValue }}" @endforeach>
    {{ $text }}
</a>
