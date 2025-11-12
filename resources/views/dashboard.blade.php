<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(count($gameSessions))
                        @include('game-session.partials._list', $gameSessions)
                    @else
                        @if(empty($slots))
                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-md text-sm text-amber-800 dark:text-amber-300">
                                🎲 No sessions available to join right now — but don’t worry!
                                <br/>
                                We’re setting up new tables soon. Drop by on <strong>Monday</strong> to see next week’s sessions or request your own game night!
                                <br/>
                                <br/>
                                <span class="italic text-amber-600 dark:text-amber-400">Good things come to those who game. </span>😉
                            </div>
                        @else
                            @include('game-session.partials._request-session')
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
