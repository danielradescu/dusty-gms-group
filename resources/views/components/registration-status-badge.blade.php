@php
    $colors = [
        'Confirmed' => 'bg-green-600/40 text-green-300 border border-green-700/50',
        'Declined' => 'bg-red-600/30 text-red-300 border border-red-700/40',
        'RemindMe2Days' => 'bg-blue-600/30 text-blue-300 border border-blue-700/50',
        'OpenPosition' => 'bg-blue-600/30 text-blue-300 border border-blue-700/50',
    ];

    $color = $colors[$status->name] ?? 'bg-gray-600/30 text-gray-300 border border-gray-700/50';
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium backdrop-blur-sm $color"
]) }}>
    {{ $status->label() }}
</span>
