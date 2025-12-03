@props(['variant' => 'primary'])

@php
    $base = '
    flex items-center justify-center
    w-full
    px-4 py-1.5 rounded-md text-base shadow-sm
    focus:outline-none focus:ring-2 transition ease-in-out duration-150
    disabled:opacity-60 disabled:cursor-not-allowed
';

    $variants = [
        'primary' => 'bg-indigo-500 text-white hover:bg-indigo-600 focus:ring-indigo-300
                      dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus:ring-indigo-800',
        'secondary' => 'border border-gray-300 bg-gray-50 text-gray-800 hover:bg-gray-100 focus:ring-gray-300
                        dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700 dark:focus:ring-gray-600',
        'tertiary' => 'border border-emerald-200 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 focus:ring-emerald-200
                       dark:border-emerald-700 dark:bg-emerald-800/40 dark:text-emerald-100
                       dark:hover:bg-emerald-700/60 dark:focus:ring-emerald-600'
    ];
@endphp

<button
    x-data="{ loading: false }"
    x-on:click="setTimeout(() => loading = true, 100)"
    x-bind:disabled="loading"
    {{ $attributes->merge(['class' => $base.' '.$variants[$variant]]) }}
>
    <!-- Normal state -->
    <span x-show="!loading" class="flex items-center gap-2">
        {{ $slot }}
    </span>

    <!-- Loading state -->
    <span x-show="loading" class="flex items-center gap-2" x-cloak>
        <svg class="animate-spin h-5 w-5 text-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        <span>Processing...</span>
    </span>
</button>
