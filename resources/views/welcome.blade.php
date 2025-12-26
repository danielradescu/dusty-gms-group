<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="Comunitatea de Board Games Iași — locul unde pasionații de jocuri de societate se întâlnesc pentru a juca, socializa și descoperi noi aventuri offline.">
    <meta name="keywords"
          content="board games Iași, jocuri de societate Iași, comunitate board games, grup board games, jocuri de masă Iași, hobby Iași, jocuri tabletop România">
    <meta name="author" content="Comunitatea Board Games Iași">
    <meta name="theme-color" content="#4f46e5">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:locale" content="ro_RO">
    <meta property="og:title" content="Comunitatea de Board Games Iași — Joacă, Conectează-te, Relaxează-te!">
    <meta property="og:description"
          content="Alătură-te comunității de board games din Iași și descoperă oameni, jocuri și momente de relaxare offline.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Comunitatea Board Games Iași">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Comunitatea de Board Games Iași">
    <meta name="twitter:description"
          content="Descoperă comunitatea de jocuri de societate din Iași — pasionați de distracție offline!">
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">

    <title>Comunitatea de Board Games Iași — Joacă, Conectează-te, Relax!</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

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






<!-- Banner -->
<section
    class="w-full bg-amber-200 dark:bg-amber-700 py-6 shadow-inner border-y border-amber-400 dark:border-amber-600">
    <div class="max-w-6xl mx-auto px-6 text-center">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <span class="text-3xl">🚧</span>
            <p class="text-lg font-semibold text-amber-900 dark:text-amber-100">
                {{ __('landing.under_construction.title') }}
            </p>
        </div>
        <p class="mt-3 text-sm text-amber-800 dark:text-amber-200">
            {{ __('landing.under_construction.subtitle') }}
        </p>
    </div>
</section>

<!-- HERO -->
<section class="w-full py-20 sm:py-28 bg-white dark:bg-gray-900 shadow-sm relative overflow-hidden">
    <img src="/images/palat.png" alt="Palatul Culturii Iași"
         class="absolute inset-0 w-full h-full object-cover opacity-10 dark:opacity-15">

    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <img src="/images/logo.png" alt="Logo"
             class="mx-auto w-24 h-auto mb-8 opacity-90">

        <h1 class="text-3xl sm:text-6xl font-semibold mb-6">
            {{ __('landing.hero.title') }}
        </h1>

        <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
            {{ __('landing.hero.subtitle') }}
        </p>

        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            {{ __('landing.hero.note') }}
        </p>
    </div>
</section>

<x-community-games />

<!-- LOGIN / INVITE -->
<section class="w-full py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-xl mx-auto px-6 text-center">
        <h2 class="text-2xl font-semibold mb-4">{{ __('landing.join.title') }}</h2>

        <p class="text-gray-600 dark:text-gray-300 mb-8">
            {!! __('landing.join.text') !!}
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <x-link-button href="{{ route('login') }}" variant="primary">
                {{ __('ui.buttons.login') }}
            </x-link-button>

            <x-link-button href="{{ route('public-join-create') }}" variant="secondary">
                {{ __('ui.buttons.invite') }}
            </x-link-button>
        </div>
    </div>
</section>

<!-- WHAT WE DO -->
<section class="w-full py-20 bg-white dark:bg-gray-900">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-center text-3xl font-semibold mb-12">{{ __('landing.features.title') }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div
                class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">📆</div>
                <h3 class="text-lg font-semibold mb-2">{{ __('landing.features.propose.title') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ __('landing.features.propose.text') }}
                </p>
            </div>

            <div
                class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">🔍</div>
                <h3 class="text-lg font-semibold mb-2">{{ __('landing.features.discover.title') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ __('landing.features.discover.text') }}
                </p>
            </div>

            <div
                class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">✔️</div>
                <h3 class="text-lg font-semibold mb-2">{{ __('landing.features.confirm.title') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ __('landing.features.confirm.text') }}
                </p>
            </div>

            <div
                class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">🔔</div>
                <h3 class="text-lg font-semibold mb-2">{{ __('landing.features.notify.title') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ __('landing.features.notify.text') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- JOIN INFO -->
<section class="w-full py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-3xl font-semibold text-center mb-12">{{ __('landing.howto.title') }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <h3 class="text-xl font-semibold mb-3">{{ __('landing.howto.invite.title') }}</h3>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                    {{ __('landing.howto.invite.text') }}
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold mb-3">{{ __('landing.howto.request.title') }}</h3>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                    {{ __('landing.howto.request.text') }}
                </p>

                <x-link-button href="{{ route('public-join-create') }}" variant="primary">
                    {{ __('ui.buttons.invite') }}
                </x-link-button>
            </div>
        </div>
    </div>
</section>

@php
    $galleryPath = public_path('images/gallery');
    $galleryImages = collect(glob($galleryPath . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE))
        ->map(fn($path) => basename($path))
        ->values();
@endphp

@if ($galleryImages->isNotEmpty())
    <section class="w-full py-5 bg-gray-100 dark:bg-gray-900 relative">
        <div class="max-w-6xl mx-auto text-center relative">
            <div
                x-data="{
                    active: 0,
                    images: {{ json_encode($galleryImages) }},
                    loaded: false,
                    next() { this.active = (this.active + 1) % this.images.length; }
                }"
                x-init="loaded = true; setInterval(() => next(), 10000)"
                class="relative overflow-hidden rounded-2xl mx-auto aspect-[16/9]
                       bg-gray-200 dark:bg-gray-800
                       shadow-[0_0_20px_rgba(0,0,0,0.4)] ring-1 ring-gray-300/40 dark:ring-gray-700/50
                       before:absolute before:inset-0 before:rounded-2xl before:shadow-inner before:shadow-black/40"
            >
                <div class="absolute inset-0 bg-gray-200 dark:bg-gray-700 animate-pulse" x-show="!loaded"></div>

                <template x-for="(image, index) in images" :key="index">
                    <img
                        x-show="active === index"
                        x-transition:enter="transition ease-in-out duration-1000"
                        x-transition:enter-start="opacity-0 scale-105"
                        x-transition:enter-end="opacity-100 scale-100"
                        :src="`/images/gallery/${image}`"
                        alt="Board game moment"
                        class="absolute inset-0 w-full h-full object-cover object-center rounded-2xl"
                        loading="lazy"
                        @load="loaded = true"
                    >
                </template>

                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent rounded-2xl pointer-events-none"></div>

                <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-20">
                    <template x-for="(image, index) in images" :key="index">
                        <button
                            @click="active = index"
                            :class="active === index ? 'bg-indigo-500 scale-125' : 'bg-gray-300 dark:bg-gray-600'"
                            class="w-3 h-3 rounded-full transition-all duration-300"
                        ></button>
                    </template>
                </div>

                <div class="absolute bottom-2 right-2 opacity-50 z-30">
                    <picture>
                        <img src="{{ asset('images/logo-iasi-stacked-dark.png') }}"
                             alt="Destination Iași"
                             class="w-20 sm:w-24 h-auto opacity-70 hover:opacity-100 transition duration-300 drop-shadow-md">
                    </picture>
                </div>
            </div>
        </div>
    </section>
@endif

<!-- FOOTER -->
<section class="w-full py-20 bg-white dark:bg-gray-800 text-center dark:border-gray-700">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-2xl sm:text-3xl font-semibold mb-6">
            {{ __('landing.footer.quote') }}
        </h2>

        <div class="flex flex-wrap items-center justify-center gap-6 mt-10 mb-6 text-sm">
            <a href="/about-us" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ __('ui.links.about') }}
            </a>

            <a href="/privacy-policy" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ __('ui.links.privacy') }}
            </a>

            <a href="/terms-of-service" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ __('ui.links.terms') }}
            </a>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ __('landing.footer.contact') }}
            <a href="mailto:{{ config('app.contact_email') }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ config('app.contact_email') }}
            </a>
        </p>

        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            © {{ date('Y') }} Comunitatea Board Games Iași. {{ __('landing.footer.rights') }}
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
            {{ __('landing.footer.signature') }}
        </p>

        <div class="mt-8 flex justify-center">
            <a href="https://boardgamegeek.com/" target="_blank" rel="noopener noreferrer"
               class="opacity-70 hover:opacity-100 transition duration-300">
                <img src="{{ asset('images/powered_by_K_01_SM.png') }}"
                     alt="BoardGameGeek Logo"
                     class="h-16 w-auto">
            </a>
        </div>
    </div>
</section>

<script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Comunitatea Board Games Iași',
        'url' => url('/'),
        'logo' => asset('images/logo.png'),
        'description' => 'Comunitatea de Board Games Iași — locul unde pasionații de jocuri de societate se întâlnesc pentru a juca, socializa și descoperi noi aventuri offline.',
        'foundingDate' => '2025',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Iași',
            'addressCountry' => 'RO',
        ],
        'sameAs' => [
            'https://www.facebook.com/groups/3722253434686316',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>

</body>
</html>
