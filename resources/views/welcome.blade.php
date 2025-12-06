<html lang="ro">
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
    <meta property="og:image" content="{{ asset('images/logo_dusty_gms.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="Comunitatea Board Games Iași">

    <!-- Twitter Cards -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Comunitatea de Board Games Iași">
    <meta name="twitter:description"
          content="Descoperă comunitatea de jocuri de societate din Iași — pasionați de distracție offline!">
    <meta name="twitter:image" content="{{ asset('images/logo_dusty_gms.png') }}">


    <title>Comunitatea de Board Games Iași — Joacă, Conectează-te, Relax!</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

<!-- Banner -->
<section
    class="w-full bg-amber-200 dark:bg-amber-700 py-6 shadow-inner border-y border-amber-400 dark:border-amber-600">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <span class="text-3xl">🚧</span>
            <p class="text-lg font-semibold text-amber-900 dark:text-amber-100">
                Platforma este în prezent în <strong>dezvoltare</strong>.
            </p>
        </div>
        <p class="mt-3 text-sm text-amber-800 dark:text-amber-200">
            Funcționalitățile și designul sunt încă în lucru. Îți mulțumim pentru răbdare și implicare în construirea
            celei mai bune experiențe pentru comunitatea noastră.
        </p>
    </div>
</section>

<!-- =============================== -->
<!-- HERO -->
<!-- =============================== -->
<section class="w-full py-20 sm:py-28 bg-white dark:bg-gray-800 shadow-sm relative overflow-hidden">
    <!-- Background Image of Iași -->
    <img src="/images/palat.png" alt="Palatul Culturii Iași"
         class="absolute inset-0 w-full h-full object-cover opacity-10 dark:opacity-15">

    <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
        <img src="/images/logo_dusty_gms.png" alt="Logo"
             class="mx-auto w-24 h-auto mb-8 opacity-90">

        <h1 class="text-3xl sm:text-5xl font-semibold mb-6">
            Joacă, conectează-te și relaxează-te — împreună, la Iași.
        </h1>

        <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
            Suntem o comunitate de pasionați de board games din Iași care ne propunem să ne întâlnim cât mai des, pentru
            a ne bucura de jocuri, prieteni și momente de relaxare offline.
        </p>

        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            Jocurile ne aduc împreună — fie că ești nou în oraș sau locuiești aici de o viață.
        </p>
    </div>
</section>

<!-- =============================== -->
<!-- LOGIN / INVITE -->
<!-- =============================== -->
<section class="w-full py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-xl mx-auto px-6 text-center">
        <h2 class="text-2xl font-semibold mb-4">Alătură-te comunității noastre</h2>

        <p class="text-gray-600 dark:text-gray-300 mb-8">
            Ești deja membru sau vrei să ni te alături?<br>
            Trimite o cerere și te vom contacta cu drag!
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <x-link-button href="{{ route('login') }}" variant="primary">
                Autentificare
            </x-link-button>

            <x-link-button href="{{ route('public-join-create') }}" variant="secondary">
                Cere o invitație
            </x-link-button>
        </div>
    </div>
</section>

<!-- =============================== -->
<!-- WHAT WE DO -->
<!-- =============================== -->
<section class="w-full py-20 bg-white dark:bg-gray-900">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-center text-3xl font-semibold mb-12">Ce poți face aici</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div
                class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">📆</div>
                <h3 class="text-lg font-semibold mb-2">Propune o sesiune</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Sugerează zilele de weekend în care vrei să joci și ajută organizatorii să aleagă momentul potrivit.
                </p>
            </div>

            <div
                class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">🔍</div>
                <h3 class="text-lg font-semibold mb-2">Descoperă sesiuni</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Răsfoiește rapid sesiunile următoare și vezi cine participă.
                </p>
            </div>

            <div
                class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">✔️</div>
                <h3 class="text-lg font-semibold mb-2">Confirmă participarea</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Un singur click pentru „Vin”, „Interesat” sau „Nu pot”.
                </p>
            </div>

            <div
                class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">🔔</div>
                <h3 class="text-lg font-semibold mb-2">Primește notificări</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Primești memento-uri prietenoase înaintea sesiunilor — să nu ratezi nicio partidă.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- =============================== -->
<!-- JOIN INFO -->
<!-- =============================== -->
<section class="w-full py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-5xl mx-auto px-6">
        <h2 class="text-3xl font-semibold text-center mb-12">Cum te poți alătura</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <h3 class="text-xl font-semibold mb-3">1. Invitație de la un membru</h3>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                    Dacă cunoști pe cineva din grup, poate trimite o invitație — membrii activi pot adăuga persoane noi.
                </p>
            </div>

            <div>
                <h3 class="text-xl font-semibold mb-3">2. Cere să te alături</h3>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                    Lasă-ne datele de contact și te vom contacta pentru o scurtă discuție prietenoasă.
                </p>

                <x-link-button href="{{ route('public-join-create') }}" variant="primary">
                    Cere o invitație
                </x-link-button>
            </div>
        </div>
    </div>
</section>

<!-- =============================== -->
<!-- FOOTER -->
<!-- =============================== -->
<section class="w-full py-20 bg-white dark:bg-gray-900 text-center border-t border-gray-200 dark:border-gray-700">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-2xl sm:text-3xl font-semibold mb-6">
            „Ne jucăm ca să ne deconectăm de la ecrane și să ne reconectăm cu oamenii.”
        </h2>

        <div class="flex flex-wrap items-center justify-center gap-6 mt-10 mb-6 text-sm">
            <a href="/about-us"
               class="text-indigo-600 dark:text-indigo-400 hover:underline">Despre noi</a>

            <a href="/privacy-policy"
               class="text-indigo-600 dark:text-indigo-400 hover:underline">Politica de confidențialitate</a>

            <a href="/terms-of-service"
               class="text-indigo-600 dark:text-indigo-400 hover:underline">Termeni și condiții</a>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400">
            Contact: <a href="mailto:{{ config('app.contact_email') }}"
                        class="text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ config('app.contact_email') }}
            </a>
        </p>

        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
            © {{ date('Y') }} Comunitatea Board Games Iași. Toate drepturile rezervate.
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
            Cu drag din Iași 💙 — pentru pasionații de board games.
        </p>

    </div>
</section>

<script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Comunitatea Board Games Iași',
        'url' => url('/'),
        'logo' => asset('images/logo_dusty_gms.png'),
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
