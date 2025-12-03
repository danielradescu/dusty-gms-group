<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🔔 {{ __('Your Notifications') }}
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400">Recent updates and messages related to your activity.</p>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-10">

            <!-- New Notifications -->
            @if ($newNotifications->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-indigo-200 dark:border-indigo-700">
                    <div class="p-6 space-y-4">
                        <h3 class="text-lg font-semibold text-indigo-600 dark:text-indigo-400 mb-4">
                            🆕 New Notifications
                        </h3>

                        @foreach ($newNotifications as $note)
                            <div
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex justify-between items-start
                                       bg-indigo-50 dark:bg-indigo-900/20 transition">
                                <div class="flex-1">
                                    @if (!empty($note->link))
                                        <a href="{{ $note->link }}" target="_blank" rel="noopener noreferrer"
                                           class="block font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $note->title ?? 'View details' }}
                                        </a>
                                    @endif

                                    <p class="font-medium text-gray-800 dark:text-gray-200 {{ $note->link ? 'mt-1' : '' }}">
                                        {{ $note->message }}
                                    </p>

                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 flex items-center gap-2">
                                        {{ optional($note->sent_at ?? $note->created_at)->diffForHumans() }}
                                        <span class="inline-flex items-center text-[10px] font-semibold uppercase px-1.5 py-0.5
                                                     bg-red-500 text-white rounded-full">NEW</span>
                                    </p>
                                </div>

                                <span
                                    class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 ml-4 whitespace-nowrap"
                                >
                                    {{ str_replace('_', ' ', ucfirst(strtolower($note->type->name))) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Old Notifications -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">
                        📜 Earlier Notifications
                    </h3>

                    @forelse ($oldNotifications as $note)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex justify-between items-start hover:bg-gray-50 dark:hover:bg-gray-800/70 transition">
                            <div class="flex-1">
                                @if (!empty($note->link))
                                    <a href="{{ $note->link }}" target="_blank" rel="noopener noreferrer"
                                       class="block font-medium text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $note->title ?? 'View details' }}
                                    </a>
                                @else
                                    <p class="block font-medium">{{ $note->title }}</p>
                                @endif

                                <p class="font-medium text-gray-800 dark:text-gray-200 {{ $note->link ? 'mt-1' : '' }}">
                                    {{ $note->message }}
                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    {{ optional($note->sent_at ?? $note->created_at)->diffForHumans() }}
                                </p>
                            </div>

                            <span
                                class="text-xs px-2 py-1 rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200 ml-4 whitespace-nowrap"
                            >
                                {{ str_replace('_', ' ', ucfirst(strtolower($note->type->name))) }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                            No earlier notifications.
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if ($oldNotifications->hasPages())
                        <div class="pt-6">
                            {{ $oldNotifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
