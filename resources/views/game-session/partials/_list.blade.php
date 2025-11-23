<div class="w-full px-3 sm:px-6 lg:px-8 py-8">
    <h2 class="text-lg font-semibold text-gray-100 mb-4 flex items-center gap-2">
        🎲 Upcoming Game Sessions
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($gameSessions as $session)
            <div class="bg-gray-800 rounded-xl shadow hover:shadow-lg border border-gray-700 p-5 flex flex-col justify-between transition-all duration-200 hover:-translate-y-1">
                <!-- Header -->
                <div>
                    <h3 class="text-lg font-semibold text-white truncate">
                        {{ $session->name }}
                    </h3>
                    <p class="text-sm text-gray-400">
                        Organized by
                        <span class="text-indigo-400 font-medium">{{ $session->organizer->name ?? 'Unknown' }}</span>
                    </p>
                </div>

                <!-- Details -->
                <div class="mt-4 space-y-1 text-sm text-gray-300">
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
                        <span class="text-xs text-gray-500 italic">{{$session->myRegistration->status->label()}}</span>
                    @else
                        <span class="text-xs text-gray-500 italic">You haven’t responded to this session yet.</span>
                    @endif
                </div>

                <!-- Participation & Status -->
                @php
                    $confirmedCount = $session->registrations->where('status', \App\Enums\RegistrationStatus::Confirmed->value)->count();
                    $status = $session->status->label(); // assuming status is an enum
                @endphp

                <div class="mt-4 flex justify-between items-center text-sm text-gray-300">
                    <!-- Players -->
                    <div class="flex items-center gap-2">
                        👥
                        <span class="font-medium text-white">{{ $confirmedCount }}</span>
                        <span>/</span>
                        <span>{{ $session->max_players }}</span>
                    </div>

                    <x-session-status-badge :status="$session->status" />
                </div>

                <!-- Action -->
                <a href="{{ route('game-session.interaction.show', $session->uuid) }}"
                   class="mt-6 w-full text-center px-4 py-2 rounded-md bg-indigo-500 text-white font-medium hover:bg-indigo-600 transition">
                    View Details
                </a>
            </div>
        @endforeach
    </div>
</div>
