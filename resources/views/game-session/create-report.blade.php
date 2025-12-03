<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🎲 Game Session Created
        </h2>
    </x-slot>

    <div class="py-12 pb-0">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">

                    <!-- Success message -->
                    <div class="mb-6">
                        <h3 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-2">
                            ✅ Your session has been created!
                        </h3>
                        @if ($gameSession->delay_until > now())
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                @if ($gameSession->delay_until > $gameSession->start_at)
                                    You can now share it privately
                                @else
                                    You can now share it privately or wait until it becomes public in 6 hours.
                                @endif
                            </p>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                You can now share it with your friends.
                            </p>
                        @endif
                    </div>

                    <!-- Session link with copy button -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Shareable Link
                        </label>
                        <div class="flex items-center gap-2">
                            <input
                                id="sessionLink"
                                type="text"
                                readonly
                                value="{{ route('game-session.interaction.show', $gameSession->uuid ?? 'missing-uuid') }}"
                                class="flex-1 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700
                                           bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-sm"
                            >
                            <button type="button"
                                    x-data
                                    @click="navigator.clipboard.writeText(document.getElementById('sessionLink').value);
                                                $dispatch('notify', { message: 'Link copied to clipboard!' })"
                                    class="px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 text-white
                                               hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                Copy
                            </button>
                        </div>

                        @if ($gameSession->delay_until > now())
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                @if ($gameSession->delay_until > $gameSession->start_at)
                                    Share this link with your friends
                                @else
                                    Share this link with your friends to invite them before it becomes public.
                                @endif
                            </p>
                        @else
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Share this link with your friends to speed up the recruitment process.
                            </p>
                        @endif
                    </div>

                    <x-link-button class="!w-auto" href="{{route('game-session.interaction.show', $gameSession->uuid)}}" variant="primary">Go to session</x-link-button>

                    <!-- Early engagement preview -->
                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3">
                            👥 Early Interest Overview
                        </h4>
                        @if ($gameSession->delay_until > $gameSession->start_at)
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                🚫 Because the starting time is greater than the moment when session should become public,
                                this game session will never go public actually for people to join.
                            </p>
                        @else
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div
                                    class="p-4 rounded-md bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-700">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <strong>Players joined:</strong>
                                    </p>
                                    <p class="text-2xl font-bold text-indigo-700 dark:text-indigo-300 mt-1">
                                        {{ $autoJoinCount ?? 0 }}
                                    </p>
                                </div>

                                <div
                                    class="p-4 rounded-md bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <strong>Players to be notified:</strong>
                                    </p>
                                    <p class="text-2xl font-bold text-amber-700 dark:text-amber-300 mt-1">
                                        {{ $notifyCount ?? 0 }}
                                    </p>
                                </div>
                            </div>

                            <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                                These numbers are based on player session requests for this week’s time slot.
                            </p>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
