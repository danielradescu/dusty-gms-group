<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('notification.update') }}" method="POST">
                    @csrf
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <section>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                                👥 Participant Notifications
                            </h3>
                                @foreach(\App\Enums\NotificationSubscriptionType::participantOptions() as $option)
                                    <label class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700
                                                hover:bg-gray-50 dark:hover:bg-indigo-700/40 transition cursor-pointer">
                                        <input
                                            type="checkbox"
                                            name="subscriptions[{{$option->value}}]"
                                            value="{{ $option->value }}"
                                            {{ in_array($option->value, $subscribedTypes) ? 'checked' : '' }}
                                            class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                                                   dark:border-gray-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-900">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ $option->label() }}
                                        </span>
                                    </label>
                                @endforeach

                        </section>
                    </div>
                    @if (Auth::user()->isOrganizer())
                        <div class="p-6 text-gray-900 dark:text-gray-100">


                            <!-- Organizer Notifications -->
                            <section>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                                    🎲 Organizer Notifications
                                </h3>

                                @foreach(\App\Enums\NotificationSubscriptionType::organizerOptions() as $option)
                                    <label class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700
                                                hover:bg-gray-50 dark:hover:bg-indigo-700/40 transition cursor-pointer">
                                        <input type="checkbox"
                                               name="subscriptions[{{$option->value}}]"
                                               value="{{ $option->value }}"
                                               {{ in_array($option->value, $subscribedTypes) ? 'checked' : '' }}
                                               class="mt-1 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                                                      dark:border-gray-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-900">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">
                                            {{ $option->label() }}
                                        </span>
                                    </label>
                                @endforeach
                            </section>
                        </div>
                    @endif
                    <div class="m-5">
                        @include('partials._errors')
                        <button type="submit"
                                class="px-4 py-2 rounded-md bg-indigo-600 hover:bg-indigo-500 text-white
                                               text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-300
                                               dark:focus:ring-indigo-800 shadow-sm">
                            💾 Save Preferences
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
