<div class="mt-5 bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl">
    <!-- Card body -->
    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">

        <!-- Session Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase">Organizer</p>
                <p>{{ $gameSession->organizer->name ?? 'Unknown' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase">Status</p>
                <p>
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $gameSession->type === \App\Enums\GameSessionType::CONFIRMED_BY_ORGANIZER
                                        ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200'
                                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100' }}">
                                    {{ $gameSession->type->label() }}
                                </span>
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase">Date</p>
                <p>{{ $gameSession->start_at->format('l, M d, Y') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase">Time</p>
                <p>{{ $gameSession->start_at->format('H:i') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase">Location</p>
                <p>{{ $gameSession->location ?? 'To be decided' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase">Players</p>
                <p>{{ $gameSession->min_players }}–{{ $gameSession->max_players }}</p>
            </div>
        </div>

        <!-- Complexity -->
        <div class="mt-4">
            <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1">Complexity</p>
            <div class="flex items-center gap-1">
                <div>
                    @for ($i = 0; $i < $gameSession->complexity->getNumber(); $i++)
                        <span class="text-purple-400 text-lg">🧠</span>
                    @endfor
                </div>

                <p><strong>{{$gameSession->complexity->label()}}</strong> - {{$gameSession->complexity->description()}}</p>
            </div>
        </div>


        @php
            $confirmedRegistrations = $registrations->where('status', \App\Enums\RegistrationStatus::Confirmed->value);
            $interestedStatuses = [
                \App\Enums\RegistrationStatus::RemindMe2Days,
                \App\Enums\RegistrationStatus::OpenPosition,
            ];

            $interestedRegistrations = $registrations->whereIn('status', $interestedStatuses);

            $declinedCount = $registrations->where('status', \App\Enums\RegistrationStatus::Declined->value)->count();
        @endphp
        <div class="mt-6 bg-gray-800/60 rounded-lg p-4 border border-gray-700">
            <h3 class="text-sm uppercase tracking-wide text-gray-400 font-semibold mb-3">
                Attendance Status
            </h3>

            <!-- Confirmed -->
            <div class="flex justify-between mb-2">
                <span class="font-medium text-gray-200 flex items-center gap-2">
                    ✅Confirmed:
                </span>
                <div class="-space-x-2">
                    @forelse($confirmedRegistrations as $r)
                        <img src="{{ asset($r->user->getPhotoURL()) }}"
                             class="inline-block w-6 h-6 rounded-full ring-2 ring-gray-900"
                             alt="Meeple">
                    @empty
                        <span class="text-gray-400 text-sm">– None –</span>
                    @endforelse
                </div>
            </div>
            <!-- Interested -->
            <div class="flex justify-between mb-2">
                <span class="font-medium text-gray-200 flex items-center gap-2">
                    👀Interested:
                </span>
                <div class="-space-x-2">
                    @forelse($interestedRegistrations as $r)
                        <img src="{{ asset($r->user->getPhotoURL()) }}"
                             class="inline-block w-6 h-6 rounded-full ring-2 ring-gray-900"
                             alt="Meeple">
                    @empty
                        <span class="text-gray-400 text-sm">– None –</span>
                    @endforelse
                </div>
            </div>
            <!-- Declined -->
            <div class="flex items-center justify-between">
                <span class="font-medium text-gray-200 flex items-center gap-2">
                    🚫Declined:
                </span>
                <span class="text-gray-400 text-sm">x{{ $declinedCount ?? 0 }}</span>
            </div>
        </div>

        <!-- User Interaction -->
        <div class="pt-6 center-items">
            @if(auth()->check())

                @if($registrationStatus)
                    <p class="mb-2">You are currently marked as <strong><span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $registrationStatus->value === \App\Enums\RegistrationStatus::Confirmed->value
                                    ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200'
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100' }}">
                                {{ $registrationStatus->label() }}
                            </span></strong>.</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Would you like to change that?</p>
                @endif

                <form action="{{ route('game-session.handle', $gameSession->uuid) }}" method="POST" class="space-y-3">
                    @csrf
                    @if ($gameSession->hasOpenPositions())
                        @if(empty($registrationStatus) || $registrationStatus?->value !== \App\Enums\RegistrationStatus::Confirmed->value)
                            <button type="submit" name="action" value="confirm"
                                    class="w-full px-4 py-2 rounded-md bg-indigo-500 text-white hover:bg-indigo-600
                                               shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                                               dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus:ring-indigo-800">
                                🎯 Count me in — reserve my seat!
                            </button>
                        @endif

                        @if(empty($registrationStatus) || $registrationStatus?->value !== \App\Enums\RegistrationStatus::RemindMe2Days->value)
                            @php
                                $hoursUntilTwoDaysBeforeEvent = (int)now()->diffInHours($gameSession->start_at->copy()->subDays(2), false)
                            @endphp

                            @if($hoursUntilTwoDaysBeforeEvent > 2)
                                <button type="submit" name="action" value="2day"
                                        class="w-full px-4 py-2 rounded-md border border-emerald-200 bg-emerald-50 text-emerald-800
                                           hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200
                                           dark:border-emerald-900 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/30">
                                    ⏰ Remind me two days before (in {{$hoursUntilTwoDaysBeforeEvent}} hours) — still deciding.
                                </button>
                            @endif

                        @endif
                    @else
                        @if(empty($registrationStatus) || $registrationStatus?->value !== \App\Enums\RegistrationStatus::OpenPosition->value)
                            <button type="submit" name="action" value="openPosition"
                                    class="w-full px-4 py-2 rounded-md border border-emerald-200 bg-emerald-50 text-emerald-800
                                               hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200
                                               dark:border-emerald-900 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/30">
                                ⏰ Let me know as soon as a position is opened
                            </button>
                        @endif
                    @endif



                    @if(empty($registrationStatus) || $registrationStatus?->value !== \App\Enums\RegistrationStatus::Declined->value)
                        <button type="submit" name="action" value="decline"
                                class="w-full px-4 py-2 rounded-md border border-gray-300 bg-gray-50 text-gray-700
                                         hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300
                                         dark:border-gray-700 dark:bg-gray-800/30 dark:text-gray-300 dark:hover:bg-gray-800/50">
                            🚫 I can’t make it
                        </button>
                    @endif
                </form>

                <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 italic leading-relaxed">
                    By interacting with this game session (for example by showing interest or confirming attendance),
                    you agree to receive related updates and notifications about the event and its status
                    from now until the session has concluded. These may include reminders, schedule adjustments,
                    and important announcements necessary for participation and coordination.
                </p>

            @else
                @php
                    // Save intended URL if user sees this section
                    session()->put('url.intended', route('show.game-session', $gameSession->uuid));
                @endphp
                <a href="{{route('login')}}" type="submit" name="action" value="confirm"
                   class="w-full px-4 py-2 rounded-md bg-indigo-500 text-white hover:bg-indigo-600
                                           shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                                           dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus:ring-indigo-800">
                    Login to interact
                </a>
            @endif
        </div>
    </div>
</div>

