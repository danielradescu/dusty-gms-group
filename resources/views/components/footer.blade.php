<footer class="mt-10 border-t border-gray-200 dark:border-gray-700 py-6 text-center text-sm text-gray-600 dark:text-gray-400">
    <div class="max-w-4xl mx-auto px-4 flex flex-col items-center gap-3">

        <!-- Copyright -->
        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
            {!! __('footer.copyright', ['year' => date('Y')]) !!}
        </p>

        <!-- Links -->
        <div class="flex flex-wrap items-center justify-center gap-4 text-xs sm:text-sm">

            <a href="{{ route('about-us') }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline hover:text-indigo-700 dark:hover:text-indigo-300">
                {{ __('ui.links.about') }}
            </a>

            <a href="{{ route('privacy-policy') }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline hover:text-indigo-700 dark:hover:text-indigo-300">
                {{ __('ui.links.privacy') }}
            </a>

            <a href="{{ route('terms-of-service') }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline hover:text-indigo-700 dark:hover:text-indigo-300">
                {{ __('ui.links.terms') }}
            </a>

            {{-- Optional register link --}}
            {{-- @if(! Auth::check()) --}}
            {{-- <a href="{{ route('register') }}" class="text-green-600 dark:text-green-400 hover:underline hover:text-green-700 dark:hover:text-green-300"> --}}
            {{--     {{ __('ui.links.register') }} --}}
            {{-- </a> --}}
            {{-- @endif --}}
        </div>

        <!-- Contact Email -->
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ __('footer.contact.label') }}
            <a href="mailto:{{ config('app.contact_email') }}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline">
                {{ config('app.contact_email') }}
            </a>
        </p>

    </div>

    <div class="mt-8 flex justify-center">
        <a href="https://boardgamegeek.com/" target="_blank" rel="noopener noreferrer"
           class="opacity-70 hover:opacity-100 transition duration-300">
            <img src="{{ asset('images/powered_by_K_01_SM.png') }}"
                 alt="{{ __('footer.powered_by.alt') }}"
                 class="h-16 w-auto">
        </a>
    </div>
</footer>
