<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        @if($joinRequest)
            <input type="hidden" name="invitation_token" value="{{ $joinRequest->invitation_token }}">
        @endif

        <!-- Info -->
        <div class="mt-4">
            <p class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                {{ __('auth/simple_register.info_text') }}
            </p>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth/simple_register.password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('auth/simple_register.confirm_password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end mt-4">
            <a href="{{ route('normal_register', request()->query()) }}"
               class="inline-block mt-2 underline text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 rounded-md transition">
                {{ __('auth/simple_register.update_link') }}
            </a>

            <x-button class="ms-4" variant="primary">
                {{ __('auth/simple_register.submit') }}
            </x-button>
        </div>

        @if (session('error'))
            <x-input-error :messages="session('error')" class="mt-2" />
        @endif
    </form>
</x-guest-layout>
