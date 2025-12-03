<div class="w-full px-3 sm:px-6 lg:px-8 py-8">
    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
        🎲 Upcoming Game Sessions
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($gameSessions as $session)
            @php
                $isHiddenForNow = $session->delay_until && $session->delay_until->isFuture();
            @endphp
            <div
                class="relative rounded-xl shadow hover:shadow-lg border p-5 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1
               {{ $isHiddenForNow ? 'bg-gray-200 dark:bg-gray-700 border-dashed border-amber-400/60 opacity-70' : 'bg-white border-gray-200 hover:border-gray-300 dark:bg-gray-800 dark:border-gray-700' }}"
            >
                <!-- Header -->
                <div>
                    <h3 class="text-lg font-semibold truncate text-gray-900 dark:text-white">
                        {{ $session->name }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Organized by
                        <span class="font-medium text-indigo-600 dark:text-indigo-400">
                            {{ $session->organizer->name ?? 'Unknown' }}
                        </span>
                    </p>
                </div>

                <!-- Details -->
                <div class="mt-4 space-y-1 text-sm text-gray-700 dark:text-gray-300">
                    <p class="flex items-center gap-2">
                        📅 <span>{{ $session->start_at->format('l, d M Y') }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        ⏰ <span>{{ $session->start_at->format('H:i') }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        📍 <span>{!! \App\Helpers\TextHelper::linkify($session->location ?? 'To be decided') !!}</span>
                    </p>
                    @if($session->myRegistration)
                        <span class="text-xs italic text-gray-500 dark:text-gray-500">
                            {{$session->myRegistration->status->label()}}
                        </span>
                    @else
                        <span class="text-xs italic text-gray-500 dark:text-gray-500">
                            You haven’t responded to this session yet.
                        </span>
                    @endif
                </div>

                <!-- Participation & Status -->
                @php
                    $confirmedCount = $session->registrations->where('status', \App\Enums\RegistrationStatus::Confirmed->value)->count();
                    $status = $session->status->label();
                @endphp

                <div class="mt-4 flex justify-between items-center text-sm text-gray-700 dark:text-gray-300">
                    <div class="flex items-center gap-2">
                        👥
                        <span class="font-medium text-gray-900 dark:text-white">{{ $confirmedCount }}</span>
                        <span>/</span>
                        <span>{{ $session->max_players }}</span>
                    </div>

                    @if($isHiddenForNow)
                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200
                            dark:bg-yellow-700/40 dark:text-yellow-100 dark:border-yellow-600/50">
                            🔒 Hidden until {{ $session->delay_until->format('d M Y, H:i') }}
                        </span>
                    @else
                        <x-session-status-badge :status="$session->status"/>
                    @endif
                </div>

                <!-- Action -->

                <x-link-button class="mt-6" href="{{ route('game-session.interaction.show', $session->uuid) }}" variant="primary">View Details</x-link-button>
            </div>
        @endforeach

    </div>
</div>
