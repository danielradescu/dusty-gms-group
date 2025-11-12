<footer class="mt-10 border-t border-gray-200 dark:border-gray-700 py-6 text-center text-sm text-gray-600 dark:text-gray-400">
    <div class="max-w-4xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
        <p class="text-xs sm:text-sm">
            © {{ date('Y') }} <span class="font-semibold text-indigo-600 dark:text-indigo-400">Dusty GMS Group</span>.
            All rights reserved.
        </p>

        <a href="{{ route('about-us') }}"
           class="text-indigo-600 dark:text-indigo-400 hover:underline hover:text-indigo-700 dark:hover:text-indigo-300 text-xs sm:text-sm">
            🌟 Learn more — About Us
        </a>
    </div>
</footer>
