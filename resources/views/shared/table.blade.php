@php
    $headers ??= [];
    $rows ??= [];
    $actions ??= null;
@endphp

<div class="overflow-x-auto bg-white shadow rounded">
    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-gray-100 border-b">
                @foreach ($headers as $key => $label)
                    <th class="text-left px-4 py-2 text-gray-600 font-semibold">
                        @if (is_array($label))
                            <span class="font-medium text-gray-600">
                                {{ $label['label'] }}
                            </span>
                        @else
                            {{ $label }}
                        @endif
                    </th>
                @endforeach
                @if ($actions)
                    <th class="text-left px-4 py-2 text-gray-600 font-semibold">Actions</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse ($rows as $row)
                <tr class="border-b hover:bg-gray-50">
                    @foreach ($headers as $key => $label)
                        <td class="px-4 py-2 text-gray-800">{{ $row[$key] ?? '' }}</td>
                    @endforeach
                    @if ($actions)
                        <td class="px-4 py-2">
                            <div class="flex items-center space-x-2">
                                {{ $actions($row) }}
                            </div>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) + ($actions ? 1 : 0) }}"
                        class="text-center px-4 py-6 text-gray-500">
                        Aucun élément trouvé.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
