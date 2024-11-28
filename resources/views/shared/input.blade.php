@php
    $label ??= null;
    $type ??= 'text';
    $class ??= '';
    $name ??= '';
    $value ??= '';
    $attributes ??= [];
@endphp

<div class="mb-6">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
        </label>
    @endif

    <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}"
        class="block w-full rounded-xs p-2 shadow-sm bg-slate-100 border-gray-300 focus:ring-pink-400 focus:border-pink-200 text-gray-900 placeholder-gray-400 sm:text-sm {{ $class }} @error($name) border-red-500 @enderror"
        @foreach ($attributes as $attr => $attrValue) {{ $attr }}="{{ $attrValue }}" @endforeach>

    @error($name)
        <p class="mt-2 text-sm text-red-500">
            {{ $message }}
        </p>
    @enderror
</div>
