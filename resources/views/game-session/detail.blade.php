<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🎲 {{ $gameSession->name }}
        </h2>
        <p class="text-sm text-gray-800 dark:text-gray-400">{{ $gameSession->description }}</p>
    </x-slot>
    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @include('game-session.partials.game-session._detail', [$gameSession, $registrations, $registrationStatus])
            @include('game-session.partials.game-session._comments', [$comments, $gameSession])
        </div>
    </div>
</x-app-layout>
