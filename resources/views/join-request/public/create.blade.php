<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('join.title') }}
        </h2>
    </x-slot>
    <div class="py-12 pb-0">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    <div class="mb-4">
                        <x-link-button class="!w-auto" href="{{ url()->previous() }}" variant="secondary">{{ __('join.form.back') }}</x-link-button>
                    </div>

                    <h3 class="text-lg font-semibold">{{ __('join.intro.heading') }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">
                        {{ __('join.intro.text') }}
                    </p>

                    @if (session('success'))
                        <div
                            class="flex items-start gap-2 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700
                                    text-emerald-800 dark:text-emerald-200 rounded-md p-3 shadow-sm">
                            <span class="text-xl">✅</span>
                            <div class="flex-1">
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div
                            class="flex items-start gap-2 bg-rose-50 dark:bg-rose-900/30 border border-rose-300 dark:border-rose-700
                                    text-rose-800 dark:text-rose-200 rounded-md p-3 shadow-sm">
                            <span class="text-xl">❌</span>
                            <div class="flex-1">
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif


                    <form action="{{ route('public-join-store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('join.form.fields.name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('join.form.fields.email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                            @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('join.form.fields.phone') }}</label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">
                            @error('phone')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="other_means_of_contact" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('join.form.fields.other_contact') }}</label>
                            <textarea name="other_means_of_contact" id="other_means_of_contact" rows="2"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('other_means_of_contact') }}</textarea>
                            @error('other_means_of_contact')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('join.form.fields.message') }}</label>
                            <textarea name="message" id="message" rows="4"
                                      placeholder="{{ __('join.form.fields.message_placeholder') }}"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                            @error('message')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-button variant="primary">{{ __('join.form.submit') }}</x-button>
                        </div>
                    </form>

                    <p class="text-xs text-gray-500 dark:text-gray-400 italic leading-relaxed mt-6">
                        {!! __('join.legal.consent', ['privacy_link' => '<a href="'.route('privacy-policy').'" class="text-indigo-500 hover:underline">'.__('ui.links.privacy').'</a>']) !!}.
                    </p>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
