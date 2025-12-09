<div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl">

    <!-- Card body -->
    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
        <div class="mb-4">
            @if(auth()->check())
                <x-link-button class="!w-auto" href="{{ route('dashboard') }}" variant="secondary">← Back to Dashboard
                </x-link-button>
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
        <hr class="mt-10 my-8 border-0 h-px bg-gradient-to-r from-transparent via-indigo-400 to-transparent dark:via-indigo-600">

        <div class="mt-8 text-center">
            <h3 class="text-sm uppercase tracking-wide font-semibold mb-4
               text-gray-700 dark:text-gray-400">
                Attendance Status
            </h3>

            <!-- Confirmed -->
            <div class="mb-6">
                <span class="block font-semibold text-lg text-gray-800 dark:text-gray-100 mb-3">
                    ✅ Confirmed:
                </span>

                <div class="flex flex-wrap justify-center gap-4">
                    @php
                        $confirmedCount = $confirmedRegistrations->count();
                        $remainingSlots = max(0, $gameSession->max_players - $confirmedCount);
                    @endphp

                    {{-- Show confirmed participants with glow --}}
                    @foreach($confirmedRegistrations as $r)
                        <div class="relative group">
                            <img src="{{ asset($r->user->getPhotoURL()) }}"
                                 class="inline-block w-16 h-16 rounded-full ring-2 ring-indigo-400 dark:ring-indigo-500
                                object-cover transition-transform duration-200 group-hover:scale-105
                                shadow-[0_0_10px_rgba(99,102,241,0.6)] dark:shadow-[0_0_10px_rgba(129,140,248,0.7)]"
                                 alt="profile image">
                        </div>
                    @endforeach

                    {{-- Empty slots --}}
                    @for($i = 0; $i < $remainingSlots; $i++)
                        <div
                            class="w-16 h-16 rounded-full border-2 border-dashed border-gray-300
                           dark:border-gray-600 flex items-center justify-center text-gray-400
                           dark:text-gray-500 text-sm">
                            ?
                        </div>
                    @endfor

                    @if($confirmedCount === 0)
                        <span class="text-gray-500 dark:text-gray-400 text-sm italic">– None –</span>
                    @endif
                </div>

                {{-- Count label --}}
                <div class="mt-3 text-sm text-gray-600 dark:text-gray-400 font-medium">
                    {{ $confirmedCount }} / {{ $gameSession->max_players }} players confirmed
                </div>
            </div>

            <!-- Interested -->
            <div class="mb-6">
                <span class="block font-semibold text-gray-800 dark:text-gray-100 mb-3">
                    👀 Interested:
                </span>

                <div class="flex flex-wrap justify-center gap-3">
                    @forelse($interestedRegistrations as $r)
                        <img src="{{ asset($r->user->getPhotoURL()) }}"
                             class="inline-block w-10 h-10 rounded-full ring-2 ring-white dark:ring-gray-900 object-cover"
                             alt="{{ $r->user->name }}">
                    @empty
                        <span class="text-gray-500 dark:text-gray-400 text-sm italic">– None –</span>
                    @endforelse
                </div>
            </div>

            <!-- Declined -->
            <div>
                <span class="block font-semibold text-gray-800 dark:text-gray-100 mb-1">
                    🚫 Declined:
                </span>
                <span class="text-gray-600 dark:text-gray-400 text-sm">
                    x{{ $declinedCount ?? 0 }}
                </span>
            </div>
        </div>


        <hr class="my-8 border-0 h-px bg-gradient-to-r from-transparent via-indigo-400 to-transparent dark:via-indigo-600">


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
                                    <x-button type="submit" name="action" value="confirm" variant="primary">🎯 Count me
                                        in — reserve my seat!
                                    </x-button>
                                @else
                                    @php
                                        $googleUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE' .
                                            '&text=' . urlencode($gameSession->name) .
                                            '&details=' . urlencode("Join us for a board game session organized by " . ($gameSession->organizer->name ?? 'Unknown')) .
                                            '&location=' . urlencode($gameSession->location ?? 'Iași') .
                                            '&dates=' . $gameSession->start_at->format('Ymd\THis') . '/' . ($gameSession->end_at ?? $gameSession->start_at->copy()->addHours(3))->format('Ymd\THis');
                                    @endphp
                                    <x-link-button
                                        href="{{ $googleUrl }}"
                                        variant="tertiary"
                                        class="flex items-center gap-2"
                                    >
                                        📅 Add to Google Calendar
                                    </x-link-button>
                                @endif

                                @if(empty($registrationStatus) || $registrationStatus?->value !== \App\Enums\RegistrationStatus::RemindMe2Days->value)
                                    @php
                                        $hoursUntilTwoDaysBeforeEvent = (int)now()->diffInHours($gameSession->start_at->copy()->subDays(2), false)
                                    @endphp

                                    @if($hoursUntilTwoDaysBeforeEvent > 2)
                                        <x-button type="submit" name="action" value="2day" variant="tertiary">
                                            ⏰ Remind me two days before (in {{$hoursUntilTwoDaysBeforeEvent}} hours) —
                                            still deciding.
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
                        <x-link-button href="{{ route('game-session.manage.edit', $gameSession->uuid) }}"
                                       variant="primary">⚙️ Manage session
                        </x-link-button>
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

