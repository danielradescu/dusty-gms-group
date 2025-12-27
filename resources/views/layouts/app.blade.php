<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @isset($meta)
            {!! $meta !!}
        @endisset
    </head>
    <body class="font-sans antialiased">
    <!-- Language Switcher -->
    <div class="absolute top-6 right-6 z-30">
        <form method="get" action="{{ url()->current() }}" class="relative group">
            <div class="relative flex items-center">
                <div class="relative">
                    <select name="lang" id="lang"
                            onchange="this.form.submit()"
                            style="color-scheme: light dark;"
                            class="appearance-none text-sm font-medium cursor-pointer
                   text-gray-900 dark:text-gray-100
                   bg-white dark:bg-gray-900
                   rounded-md pr-10 pl-3 py-1.5
                   border-0 outline-none ring-0
                   focus:outline-none focus:ring-2 focus:ring-indigo-500
                   transition">
                        <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                        <option value="ro" {{ app()->getLocale() === 'ro' ? 'selected' : '' }}>🇷🇴 Română</option>
                    </select>

                    <!-- Custom caret -->
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 dark:text-gray-300 pointer-events-none"
                         fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>



                <!-- Custom caret -->
                <svg class="absolute right-3 w-4 h-4 text-gray-500 dark:text-gray-300 pointer-events-none"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </form>
    </div>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            @if (Auth::user())
                @include('layouts.navigation')
            @else
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="/">
                            <x-application-logo
                                class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                        </a>
                    </div>
                </div>
            @endif
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            @if(auth()->check())
                <x-participant-confirmed-session-notice />
                <x-organizer-sessions-notice />
            @endif
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
                @include('components.footer')
        </div>
    </body>
</html>
