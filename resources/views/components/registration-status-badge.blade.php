@php
    $colors = [
        'Confirmed' =>
            'bg-green-100 text-green-800 border border-green-200
             dark:bg-green-600/40 dark:text-green-300 dark:border-green-700/50',

        'Declined' =>
            'bg-red-100 text-red-800 border border-red-200
             dark:bg-red-600/30 dark:text-red-300 dark:border-red-700/40',

        'RemindMe2Days' =>
            'bg-blue-100 text-blue-800 border border-blue-200
             dark:bg-blue-600/30 dark:text-blue-300 dark:border-blue-700/50',

        'OpenPosition' =>
            'bg-blue-100 text-blue-800 border border-blue-200
             dark:bg-blue-600/30 dark:text-blue-300 dark:border-blue-700/50',
    ];

    $color = $colors[$status->name] ??
        'bg-gray-100 text-gray-800 border border-gray-200
         dark:bg-gray-600/30 dark:text-gray-300 dark:border-gray-700/50';
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium backdrop-blur-sm $color"
]) }}>
    {{ $status->label() }}
</span>
