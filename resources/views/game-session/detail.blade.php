<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🎲 {{ $gameSession->name }}
        </h2>
        <p class="text-sm text-gray-800 dark:text-gray-400">{{ $gameSession->description }}</p>
    </x-slot>
    <x-slot name="meta">
        @if(isset($gameSession))
            <meta property="og:title" content="{{ $gameSession->name }} – Board Game Session">
            <meta property="og:description"
                  content="Join us for a board-gaming session on {{ $gameSession->start_at->format('l, M d, Y') }} at {{ $gameSession->location ?? 'TBD' }}. Players: {{ $gameSession->min_players }}–{{ $gameSession->max_players }}.">
            <meta property="og:type" content="website">
            <meta property="og:url" content="{{ request()->url() }}">

            {{-- Image (optional) --}}
            <meta property="og:image" content="{{ asset('images/complexity/' . $gameSession->complexity->label() . '.png') }}">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">

            {{-- Twitter --}}
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="{{ $gameSession->name }}">
            <meta name="twitter:description"
                  content="Join us for a session on {{ $gameSession->start_at->format('l, M d, Y') }}.">
            <meta name="twitter:image" content="{{ asset('images/complexity/' . $gameSession->complexity->label() . '.png') }}">
        @endif
    </x-slot>
    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @include('game-session.partials._detail', [$gameSession, $registrations, $registrationStatus])
            @include('game-session.partials._comments', [$comments, $gameSession])
        </div>
    </div>
</x-app-layout>
