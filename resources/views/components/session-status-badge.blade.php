@php
    $colors = [
        'CONFIRMED_BY_ORGANIZER' => 'bg-green-600/40 text-green-300 border border-green-700/50',
        'CANCELLED' => 'bg-red-600/30 text-red-300 border border-red-700/40',
        'RECRUITING_PARTICIPANTS' => 'bg-yellow-600/30 text-yellow-200 border border-yellow-700/40',
    ];

    $color = $colors[$status->name] ?? 'bg-gray-600/30 text-gray-300 border border-gray-700/50';
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center px-2 py-1 rounded-full text-xs font-medium $color"
]) }}>
    {{ $status->label() }}
</span>
