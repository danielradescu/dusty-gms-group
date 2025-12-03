<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Request to Join the Community') }}
        </h2>
    </x-slot>

    ```
    <div class="py-12 pb-0">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    <div class="mb-4">
                        <x-link-button class="!w-auto" href="/" variant="secondary">← Back</x-link-button>
                    </div>

                    <h3 class="text-lg font-semibold">Join the Iași Board-Gaming Community</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                        If you'd like to become part of our local gaming community, please fill out this short form.
                        We’ll review your request and get back to you. This form is intended for people who
                        want to participate in our board game sessions and stay updated on events.
                    </p>

                    @if (session('success'))
                        <div class="bg-emerald-100 dark:bg-emerald-800/30 border border-emerald-200 dark:border-emerald-700 text-emerald-700 dark:text-emerald-300 rounded-md p-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('public-join-store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone (optional)</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('phone')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="other_means_of_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other means of contact (optional)</label>
                            <textarea name="other_means_of_contact" id="other_means_of_contact" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('other_means_of_contact') }}</textarea>
                            @error('other_means_of_contact')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tell us a bit about you</label>
                            <textarea name="message" id="message" rows="4"
                                      placeholder="How did you find out about us? What kind of games do you enjoy?"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                            @error('message')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-button variant="primary">✉️ Submit Request</x-button>
                        </div>
                    </form>

                    <p class="text-xs text-gray-500 dark:text-gray-400 italic leading-relaxed mt-6">
                        By submitting this form, you consent to the processing of your data for the purpose of community
                        membership review and communication about your request. We collect IP and browser information
                        only to prevent spam and abuse. For details, see our
                        <a href="{{ route('privacy-policy') }}" class="text-indigo-500 hover:underline">Privacy Policy</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
