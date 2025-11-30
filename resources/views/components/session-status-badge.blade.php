@php
        $colors = [
        'CONFIRMED_BY_ORGANIZER' =>
            // light mode
            'bg-green-100 text-green-800 border border-green-200
             dark:bg-green-700/50 dark:text-green-100 dark:border-green-600/60',

        'CANCELLED' =>
            'bg-red-100 text-red-800 border border-red-200
             dark:bg-red-700/40 dark:text-red-100 dark:border-red-600/50',

        'RECRUITING_PARTICIPANTS' =>
            'bg-yellow-100 text-yellow-800 border border-yellow-200
             dark:bg-yellow-700/40 dark:text-yellow-100 dark:border-yellow-600/50',
    ];


    $color = $colors[$status->name] ?? 'bg-gray-100 text-gray-800 border border-gray-200 dark:bg-gray-600/30 dark:text-gray-300 dark:border-gray-700/50';
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center px-2 py-1 rounded-full text-xs font-medium $color"
]) }}>
    {{ $status->label() }}
</span>
