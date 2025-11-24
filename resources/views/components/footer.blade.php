<footer class="mt-10 border-t border-gray-200 dark:border-gray-700 py-6 text-center text-sm text-gray-600 dark:text-gray-400">
    <div class="max-w-4xl mx-auto px-4 flex flex-col items-center gap-3">

        <!-- Copyright -->
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
            © {{ date('Y') }} Iasi Board Gaming Community. All rights reserved.
        </p>

        <!-- Links -->
        <div class="flex flex-wrap items-center justify-center gap-4 text-xs sm:text-sm">

            <a href="{{ route('about-us') }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline hover:text-indigo-700 dark:hover:text-indigo-300">
                🌟 About Us
            </a>

            <a href="{{ route('privacy-policy') }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline hover:text-indigo-700 dark:hover:text-indigo-300">
                Privacy Policy
            </a>

            <a href="{{ route('terms-of-service') }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline hover:text-indigo-700 dark:hover:text-indigo-300">
                Terms of Service
            </a>
            @if(! Auth::check())
                <a href="{{ route('register') }}"
                   class="text-green-600 dark:text-green-400 hover:underline hover:text-green-700 dark:hover:text-green-300 text-xs sm:text-sm">
                    ➕ Register
                </a>
            @endif
        </div>

        <!-- Contact Email -->
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Contact us:
            <a href="mailto:{{env('APP_CONTACT_EMAIL')}}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline">
                {{env('APP_CONTACT_EMAIL')}}
            </a>
        </p>

    </div>
</footer>
