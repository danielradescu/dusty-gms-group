<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Iași Board Games — Play Together</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Tailwind / App CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100">

<!-- =============================== -->
<!-- SECTION A — HERO -->
<!-- =============================== -->
<section class="w-full py-20 sm:py-28 bg-white dark:bg-gray-800 shadow-sm">
    <div class="max-w-4xl mx-auto px-6 text-center">

        <img src="/images/logo_dusty_gms.png" alt="Logo"
             class="mx-auto w-24 h-auto mb-8 opacity-90">

        <h1 class="text-3xl sm:text-5xl font-semibold mb-6">
            Play, connect, and unwind — together.
        </h1>

        <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
            We’re a community of board game enthusiasts from Iași who meet weekly
            to share great games, good company, and a few hours away from screens.
        </p>

        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            Built by the community, for the community.
        </p>

    </div>
</section>


<!-- =============================== -->
<!-- SECTION: LOGIN + INVITE -->
<!-- =============================== -->
<section class="w-full py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-xl mx-auto px-6 text-center">

        <h2 class="text-2xl font-semibold mb-4">Join our Community</h2>

        <p class="text-gray-600 dark:text-gray-300 mb-8">
            Already part of the group? Log in using your magic link.
            New here? Request an invitation — we’d be happy to meet you!
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/login"
               class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow">
                Log In
            </a>

            <a href="/request-invite"
               class="px-6 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600
                          text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600
                          font-medium rounded-md shadow">
                Request an Invitation
            </a>
        </div>

    </div>
</section>


<!-- =============================== -->
<!-- SECTION B — WHAT WE DO -->
<!-- =============================== -->
<section class="w-full py-20 bg-white dark:bg-gray-900">
    <div class="max-w-6xl mx-auto px-6">

        <h2 class="text-center text-3xl font-semibold mb-12">What You Can Do</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Feature -->
            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">📆</div>
                <h3 class="text-lg font-semibold mb-2">Request a Session</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Suggest weekend days you'd like to play. Help organizers pick the best time.
                </p>
            </div>

            <!-- Feature -->
            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">🔍</div>
                <h3 class="text-lg font-semibold mb-2">Discover Sessions</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Quickly browse upcoming gaming sessions and see who’s joining.
                </p>
            </div>

            <!-- Feature -->
            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">✔️</div>
                <h3 class="text-lg font-semibold mb-2">Confirm Attendance</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    One click to say “I’m in,” “Interested,” or “Can’t make it.”
                </p>
            </div>

            <!-- Feature -->
            <div class="p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                <div class="text-4xl mb-3">🔔</div>
                <h3 class="text-lg font-semibold mb-2">Get Reminders</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Receive friendly reminders before sessions start so you never miss a game.
                </p>
            </div>

        </div>

    </div>
</section>


<!-- =============================== -->
<!-- SECTION C — HOW TO JOIN -->
<!-- =============================== -->
<section class="w-full py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-5xl mx-auto px-6">

        <h2 class="text-3xl font-semibold text-center mb-12">How to Join</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">

            <!-- Invitation from member -->
            <div>
                <h3 class="text-xl font-semibold mb-3">1. Invitation from an Existing Member</h3>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                    If you already know someone in the community, they can invite you directly.
                    Members who attended at least one session (Level 2+) can send invitations.
                </p>
            </div>

            <!-- Request to join -->
            <div>
                <h3 class="text-xl font-semibold mb-3">2. Request to Join</h3>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed mb-4">
                    New to the group? Leave your contact details and we’ll get in touch for a short, friendly chat — by message or phone — to introduce ourselves.
                </p>

                <a href="/request-invite"
                   class="inline-block px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md shadow font-medium">
                    Request an Invitation
                </a>
            </div>

        </div>

    </div>
</section>


<!-- =============================== -->
<!-- SECTION G — CLOSING MOTTO -->
<!-- =============================== -->
<section class="w-full py-20 bg-white dark:bg-gray-900 text-center">
    <div class="max-w-3xl mx-auto px-6">

        <h2 class="text-2xl sm:text-3xl font-semibold mb-6">
            “We play games to disconnect from screens and reconnect with people.”
        </h2>

        <p class="text-gray-600 dark:text-gray-400 mt-6 text-sm">
            © 2025 Iași Board Gaming Community — Built by the community, for the community.
        </p>

        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            Contact: <a href="mailto:iasi.boardgames@gmail.com"
                        class="text-indigo-600 dark:text-indigo-400 hover:underline">iasi.boardgames@gmail.com</a>
        </p>
    </div>
</section>

</body>
</html>
