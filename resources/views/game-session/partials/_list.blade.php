@foreach($gameSessions as $session)
    <div class="max-w-3xl mx-auto mt-6 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition">
        <div class="space-y-2">
            <div><strong>Name:</strong> {{ $session->name }}</div>
            <div><strong>Organizer:</strong> {{ $session->organizer->name ?? 'Unknown' }}</div>
            <div><strong>Date:</strong> {{ $session->start_at->format('l, M d, Y') }}</div>
            <div><strong>Starting at:</strong> {{ $session->start_at->format('H:i') }}</div>
            <div><strong>Location:</strong> {{ $session->location ?? 'To be decided' }}</div>
            <div><strong>Expected:</strong> {{ $session->min_players }}–{{ $session->max_players }} players</div>
        </div>

        <div class="mt-4">
            <a href="{{route('show.game-session', $session->uuid)}}"
               class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium">
                Click here for more info...
            </a>
        </div>
    </div>
    <hr class="bg-white"/>
@endforeach
