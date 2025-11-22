<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🔔 {{ __('Your Notifications') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">

                    @forelse ($notifications as $note)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex justify-between items-center">
                            <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">
                                    {{ $note['message'] }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $note['date']->diffForHumans() }}
                                </p>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                {{ str_replace('_', ' ', ucfirst(strtolower($note['type']))) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                            You have no notifications yet.
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
