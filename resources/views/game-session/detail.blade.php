<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="pl-6 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $gameSession->name }}</h2>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="max-w-3xl mx-auto mt-6 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition">
                        <div class="space-y-2">
                            <div><strong>Organizer:</strong> {{ $gameSession->organizer->name ?? 'Unknown' }}</div>
                            <div><strong>Date:</strong> {{ $gameSession->start_at->format('l, M d, Y') }}</div>
                            <div><strong>Starting at:</strong> {{ $gameSession->start_at->format('H:i') }}</div>
                            <div><strong>Location:</strong> {{ $gameSession->location ?? 'To be decided' }}</div>
                            <div><strong>Expected:</strong> {{ $gameSession->min_players }}–{{ $gameSession->max_players }} players</div>
                            <div><strong>Complexity</strong>
                                <span class="inline-flex items-center gap-1">
                                    @for ($i = 0; $i < round($gameSession->complexity); $i++)
                                        <span class="text-purple-400 text-lg">🧠</span>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-400">{{ number_format($gameSession->complexity, 2) }}</span>
                                </span>
                            </div>


                            <div>{{$gameSession->description}}</div>
                            <div><strong>Status:</strong> {{$gameSession->type->label()}}</div>
                            <div><strong>People now:</strong> <img class="h-10 w-10 rounded-full object-cover" src="{{ asset(Auth::user()->getPhotoURL()) }}" alt="Profile Image"> <img class="h-10 w-10 rounded-full object-cover" src="{{ asset(Auth::user()->getPhotoURL()) }}" alt="Profile Image"> <img class="h-10 w-10 rounded-full object-cover" src="{{ asset(Auth::user()->getPhotoURL()) }}" alt="Profile Image"></div>
                            <div><strong></strong> </div>
                            <div><strong></strong> </div>
                            <div><strong></strong> </div>
                            <div><strong></strong> </div>
                            <div><strong></strong> </div>
                            <div><strong></strong> </div>
                        </div>

                        <div class="mt-4">
                            <form action="{{ route('game-session.handle', $gameSession->uuid) }}" method="POST" class="max-w-md space-y-3">
                                @csrf

                                <button type="submit" name="action" value="confirm"
                                        class="w-full px-4 py-2 rounded-md bg-indigo-500 text-white hover:bg-indigo-600
                                               shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-300
                                               dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus:ring-indigo-800">
                                    🎯 Count me in — reserve my seat!
                                </button>

                                <button type="submit" name="action" value="1day"
                                        class="w-full px-4 py-2 rounded-md border border-emerald-200 bg-emerald-50 text-emerald-800
                                           hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-200
                                           dark:border-emerald-900 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/30">
                                    🕓 Remind me a day before — I might join.
                                </button>

                                <button type="submit" name="action" value="2days"
                                        class="w-full px-4 py-2 rounded-md border border-amber-200 bg-amber-50 text-amber-800
                                           hover:bg-amber-100 focus:outline-none focus:ring-2 focus:ring-amber-200
                                           dark:border-amber-900 dark:bg-amber-900/20 dark:text-amber-300 dark:hover:bg-amber-900/30">
                                    ⏰ Remind me two days before — still deciding.
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
