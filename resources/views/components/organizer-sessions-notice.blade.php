<div class="{{ request()->routeIs('dashboard') ? 'max-w-7xl' : 'max-w-5xl' }} mx-auto sm:px-6 lg:px-8">
    @if($pendingSessions->isNotEmpty())
        <div
            class="bg-amber-100 border-l-4 border-amber-500 text-amber-900 dark:bg-amber-900/40 dark:border-amber-500 dark:text-amber-100 p-4 mt-1 rounded-md shadow-sm">
            <div class="flex items-start gap-3">
                <div class="text-2xl">⚠️</div>
                <div class="flex-1">
                    <h3 class="font-semibold text-sm uppercase tracking-wide mb-1">Sessions Require Attention</h3>
                    <p class="text-sm leading-relaxed mb-3">
                        You have one or more game sessions that need to be <strong>confirmed</strong> or <strong>canceled</strong>.
                        Once enough players have confirmed, please confirm the session — otherwise, cancel it to notify
                        participants.
                    </p>

                    <div class="space-y-2">
                        @foreach($pendingSessions as $session)
                            <div
                                class="flex items-center justify-between bg-white dark:bg-gray-800/70 rounded-md px-3 py-2 shadow-sm border border-amber-200 dark:border-amber-700">
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                        🎲 {{ $session->name }}
                                    </p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                        {{ $session->start_at->format('l, M d, H:i') }} @if($session->location)
                                            • {!! \App\Helpers\TextHelper::linkify($session->location ?? 'To be decided') !!}
                                        @endif
                                    </p>
                                </div>

                                <a href="{{ route('game-session.manage.edit', $session->uuid) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-md bg-amber-500 text-white hover:bg-amber-600
                                      focus:outline-none focus:ring-2 focus:ring-amber-400 shadow-sm transition text-sm">
                                    Manage
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($finishedSessions->isNotEmpty())
        <div
            class="bg-rose-100 border-l-4 border-rose-600 text-rose-900 dark:bg-rose-900/40 dark:border-rose-500 dark:text-rose-100 p-4 mt-1 rounded-md shadow-sm">
            <div class="flex items-start gap-3">
                <div class="text-2xl">🚨</div>
                <div class="flex-1">
                    <h3 class="font-semibold text-sm uppercase tracking-wide mb-1">Session Outcome Needed</h3>
                    <p class="text-sm leading-relaxed mb-3">
                        The following sessions have already taken place but haven’t been marked as
                        <strong>Succeeded</strong> or <strong>Failed</strong>.
                        Please update their final status so that records remain accurate.
                    </p>

                    <div class="space-y-2">
                        @foreach($finishedSessions as $session)
                            <div
                                class="flex items-center justify-between bg-white dark:bg-gray-800/70 rounded-md px-3 py-2 shadow-sm border border-rose-200 dark:border-rose-700">
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

                                <a href="{{ route('game-session.manage.edit', $session->uuid) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-md bg-rose-600 text-white hover:bg-rose-700
                                      focus:outline-none focus:ring-2 focus:ring-rose-400 shadow-sm transition text-sm">
                                    Finalize
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
