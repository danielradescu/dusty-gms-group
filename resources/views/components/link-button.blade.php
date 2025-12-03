@props([
    'variant' => 'primary',
    'href' => '#',
])

@php
    $base = '
        inline-flex items-center justify-center
        w-full
        px-4 py-1.5 rounded-md text-base shadow-sm
        focus:outline-none focus:ring-2 transition ease-in-out duration-150
    ';

    $variants = [
        'primary' => 'bg-indigo-500 text-white hover:bg-indigo-600 focus:ring-indigo-300
                      dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus:ring-indigo-800',
        'secondary' => 'border border-gray-300 bg-gray-50 text-gray-800 hover:bg-gray-100 focus:ring-gray-300
                        dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-gray-600',
        'tertiary' => 'border border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 focus:ring-emerald-200
                       dark:border-emerald-700 dark:bg-emerald-800/40 dark:text-emerald-100
                       dark:hover:bg-emerald-700/60 dark:focus:ring-emerald-600',
    ];
@endphp

<a href="{{ $href }}"
{{--    {{ $attributes->merge(['class' => $base . ' ' . ($variants[$variant] ?? $variants['primary'])]) }}>--}}
    {{ $attributes->class($base . ' ' . ($variants[$variant] ?? $variants['primary'])) }}>
    {{ $slot }}
</a>
