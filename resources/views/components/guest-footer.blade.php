<footer class="mt-10 border-t border-gray-200 dark:border-gray-700 py-6 text-center text-sm text-gray-600 dark:text-gray-400">
    <div class="max-w-4xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-3">

        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 text-center">
            © {{ date('Y') }} Iasi Board Gaming Community. All rights reserved.
        </p>

        <div class="flex items-center gap-4">
            <a href="{{ route('about-us') }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline hover:text-indigo-700 dark:hover:text-indigo-300 text-xs sm:text-sm">
                🌟 About Us
            </a>

            <a href="{{ route('register') }}"
               class="text-green-600 dark:text-green-400 hover:underline hover:text-green-700 dark:hover:text-green-300 text-xs sm:text-sm">
                ➕ Register
            </a>
        </div>
    </div>
</footer>
