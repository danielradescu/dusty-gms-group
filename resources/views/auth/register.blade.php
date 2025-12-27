<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        @if($joinRequest)
            <input type="hidden" name="invitation_token" value="{{ $joinRequest->invitation_token }}">
        @endif

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('auth/register.name')" />
            <x-text-input id="name" class="block mt-1 w-full"
                          type="text" name="name" :value="old('name')" required
                          autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('auth/register.email')" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email" name="email" :value="old('email')" required
                          autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('auth/register.password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password" name="password" required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('auth/register.confirm_password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password" name="password_confirmation" required
                          autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Submit -->
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900
                      dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2
                      focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
               href="{{ route('login') }}">
                {{ __('auth/register.already_registered') }}
            </a>

            <x-button class="ms-4" variant="primary">
                {{ __('auth/register.submit') }}
            </x-button>
        </div>

        @if (session('error'))
            <x-input-error :messages="session('error')" class="mt-2" />
        @endif
    </form>
</x-guest-layout>
