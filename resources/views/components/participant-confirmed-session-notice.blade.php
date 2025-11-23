<div class="{{ request()->routeIs('dashboard') ? 'max-w-7xl' : 'max-w-5xl' }} mx-auto sm:px-6 lg:px-8">
    @if($confirmedSessions->isNotEmpty())
        <div
            class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-900 dark:bg-emerald-900/40 dark:border-emerald-500 dark:text-emerald-100 p-4 mt-1 rounded-md shadow-sm">
            <div class="flex items-start gap-3">
                <div class="text-2xl">✅</div>
                <div class="flex-1">
                    <h3 class="font-semibold text-sm uppercase tracking-wide mb-1">
                        Upcoming Sessions You’re Attending
                        <span class="text-xs font-normal text-gray-600 dark:text-gray-400">
                        ({{ $confirmedSessions->count() }})
                    </span>
                    </h3>
                    <p class="text-sm leading-relaxed mb-3">
                        You’re confirmed for the following upcoming game sessions.
                        Make sure to check the time, location, and stay updated with any organizer announcements.
                    </p>

                    <div class="space-y-2">
                        @foreach($confirmedSessions as $session)
                            <div
                                class="flex items-center justify-between bg-white dark:bg-gray-800/70 rounded-md px-3 py-2 shadow-sm border border-emerald-200 dark:border-emerald-700">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                        🎲 {{ $session->name }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $session->start_at->format('l, M d, H:i') }}
                                        @if($session->location)
                                            • {!! \App\Helpers\TextHelper::linkify($session->location ?? 'To be decided') !!}
                                        @endif
                                    </p>
                                </div>

                                <a href="{{ route('game-session.interaction.show', $session->uuid) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-md bg-emerald-500 text-white hover:bg-emerald-600
                                      focus:outline-none focus:ring-2 focus:ring-emerald-400 shadow-sm transition text-sm">
                                    View
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
