<div class="w-full px-3 sm:px-6 lg:px-8 py-8">
    <h2 class="text-lg font-semibold text-gray-100 mb-4 flex items-center gap-2">
        🎲 Upcoming Game Sessions
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($gameSessions as $session)
            <div class="bg-gray-800 rounded-xl shadow hover:shadow-lg border border-gray-700 p-5 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-white truncate">
                        {{ $session->name }}
                    </h3>
                    <p class="text-sm text-gray-400">
                        Organized by <span class="text-indigo-400 font-medium">{{ $session->organizer->name ?? 'Unknown' }}</span>
                    </p>
                </div>

                <div class="mt-4 space-y-1 text-sm text-gray-300">
                    <p class="flex items-center gap-2">
                        📅 <span>{{ $session->start_at->format('l, M d, Y') }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        ⏰ <span>{{ $session->start_at->format('H:i') }}</span>
                    </p>
                    <p class="flex items-center gap-2">
                        📍 <span>{{ $session->location ?? 'To be decided' }}</span>
                    </p>
                </div>

                <div class="mt-4 flex justify-between items-center text-sm text-gray-400">
                    <div>👥 {{ $session->min_players }}–{{ $session->max_players }} players</div>
                </div>

                <a href="{{ route('show.game-session', $session->uuid) }}"
                   class="mt-6 w-full text-center px-4 py-2 rounded-md bg-indigo-500 text-white font-medium hover:bg-indigo-600 transition">
                    View Details
                </a>
            </div>
        @endforeach
    </div>
</div>
