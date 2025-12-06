<div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl">

    <!-- Card body -->
    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
        <div class="mb-4">
            @if(auth()->check())
                <x-link-button class="!w-auto" href="{{ route('dashboard') }}" variant="secondary">← Back to Dashboard</x-link-button>
            @endif
        </div>
        <!-- Session Overview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase">Organizer</p>
                <p>{{ $gameSession->organizer->name ?? 'Unknown' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-semibold uppercase">Status</p>
                <p>
                    <x-session-status-badge :status="$gameSession->status"/>
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
                <p>{!! \App\Helpers\TextHelper::linkify($gameSession->location ?? 'To be decided') !!}</p>
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

                <p><strong>{{$gameSession->complexity->label()}}</strong> - {{$gameSession->complexity->description()}}
                </p>
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
        <div class="mt-6 rounded-lg p-4 border
            bg-white/80 border-gray-200
            dark:bg-gray-800/60 dark:border-gray-700">

            <h3 class="text-sm uppercase tracking-wide font-semibold mb-3
               text-gray-700 dark:text-gray-400">
                Attendance Status
            </h3>

            <!-- Confirmed -->
            <div class="flex justify-between mb-2">
                <span class="font-medium flex items-center gap-2
                             text-gray-800 dark:text-gray-200">
                    ✅ Confirmed:
                </span>
                        <div class="-space-x-2">
                            @forelse($confirmedRegistrations as $r)
                                <img src="{{ asset($r->user->getPhotoURL()) }}"
                                     class="inline-block w-6 h-6 rounded-full ring-2
                                    ring-white dark:ring-gray-900"
                                     alt="Meeple">
                            @empty
                                <span class="text-gray-500 dark:text-gray-400 text-sm">– None –</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Interested -->
                    <div class="flex justify-between mb-2">
                <span class="font-medium flex items-center gap-2
                             text-gray-800 dark:text-gray-200">
                    👀 Interested:
                </span>
                        <div class="-space-x-2">
                            @forelse($interestedRegistrations as $r)
                                <img src="{{ asset($r->user->getPhotoURL()) }}"
                                     class="inline-block w-6 h-6 rounded-full ring-2
                                    ring-white dark:ring-gray-900"
                                     alt="Meeple">
                            @empty
                                <span class="text-gray-500 dark:text-gray-400 text-sm">– None –</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Declined -->
                    <div class="flex items-center justify-between">
                <span class="font-medium flex items-center gap-2
                             text-gray-800 dark:text-gray-200">
                    🚫 Declined:
                </span>
                        <span class="text-gray-600 dark:text-gray-400 text-sm">
                    x{{ $declinedCount ?? 0 }}
                </span>
            </div>
        </div>


        <!-- User Interaction -->
        <div class="pt-6 center-items">
            @if(auth()->check())
                @if ($gameSession->isEditable())
                    @if($registrationStatus)
                        <p class="mb-2">You are currently marked as
                            <x-registration-status-badge :status="$registrationStatus"/>
                        </p>
                        @if ($gameSession->organized_by != auth()->user()->id)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Would you like to change that?</p>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Would you like to change that? As
                                an
                                organizer you need to assign another user as organizer before leaving this game
                                session.</p>
                        @endif
                    @endif
                    @if ($gameSession->organized_by != auth()->user()->id)
                        <form action="{{ route('game-session.interaction.store', $gameSession->uuid) }}" method="POST"
                              class="space-y-3">
                            @csrf
                            @if ($gameSession->hasOpenPositions())
                                @if(empty($registrationStatus) || $registrationStatus?->value !== \App\Enums\RegistrationStatus::Confirmed->value)
                                    <x-button type="submit" name="action" value="confirm" variant="primary">🎯 Count me in — reserve my seat!</x-button>
                                @endif

                                @if(empty($registrationStatus) || $registrationStatus?->value !== \App\Enums\RegistrationStatus::RemindMe2Days->value)
                                    @php
                                        $hoursUntilTwoDaysBeforeEvent = (int)now()->diffInHours($gameSession->start_at->copy()->subDays(2), false)
                                    @endphp

                                    @if($hoursUntilTwoDaysBeforeEvent > 2)
                                        <x-button type="submit" name="action" value="2day" variant="tertiary">
                                            ⏰ Remind me two days before (in {{$hoursUntilTwoDaysBeforeEvent}} hours) — still deciding.
                                        </x-button>
                                    @endif
                                @endif
                            @else

                                @if(empty($registrationStatus)
                                            || (
                                                $registrationStatus?->value !== \App\Enums\RegistrationStatus::OpenPosition->value
                                                && $registrationStatus?->value !== \App\Enums\RegistrationStatus::Confirmed->value
                                                )

                                            )
                                    <x-button type="submit" name="action" value="openPosition" variant="tertiary">
                                        ⏰ Let me know as soon as a position is opened
                                    </x-button>
                                @endif
                            @endif



                            @if(empty($registrationStatus) || $registrationStatus?->value !== \App\Enums\RegistrationStatus::Declined->value)
                                <x-button type="submit" name="action" value="decline" variant="secondary">
                                    🚫 I can’t make it
                                </x-button>
                            @endif
                        </form>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 italic leading-relaxed">
                            By interacting with this game session (for example by showing interest or confirming
                            attendance),
                            you agree to receive related updates and notifications about the event and its status
                            from now until the session has concluded. These may include reminders, schedule adjustments,
                            and important announcements necessary for participation and coordination.
                        </p>
                    @else
                        <x-link-button href="{{ route('game-session.manage.edit', $gameSession->uuid) }}" variant="primary">⚙️ Manage session</x-link-button>
                    @endif
                @else
                    <p class="text-xs text-gray-500 dark:text-gray-400 italic leading-relaxed">
                        Organizer note: {{$gameSession->note}}
                    </p>
                @endif
            @else
                @php
                    // Save intended URL if user sees this section
                    session()->put('url.intended', route('game-session.interaction.show', $gameSession->uuid));
                @endphp
                <x-link-button href="{{route('login')}}" variant="primary">Login to interact</x-link-button>
            @endif
        </div>
    </div>
</div>

