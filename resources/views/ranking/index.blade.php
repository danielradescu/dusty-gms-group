<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🏆 Player Rankings
        </h2>
    </x-slot>

    <div class="py-12 pb-0">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-xl p-6 text-gray-900 dark:text-gray-100 space-y-6">

                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Overall Standings</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Updated {{ now()->format('M d, Y H:i') }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm table-fixed">
                        <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-gray-500 dark:text-gray-400 uppercase text-xs tracking-wide">
                            <th class="py-2 px-2">#</th>
                            <th class="py-2 px-2">Title</th>
                            <th class="py-2 px-2">Player</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr
                                    @class([
                                        // Base row styling
                                        'border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition',
                                        // Highlight logged-in user row
                                        'bg-gradient-to-r from-indigo-50 to-white dark:from-indigo-900/30 dark:to-gray-800 font-semibold shadow-inner' => auth()->id() === $user->id,
                                    ])
                                >
                                    <td class="py-2 px-2 text-center">
                                        {{-- Rank display --}}
                                        @if($index === 0)
                                            🥇
                                        @elseif($index === 1)
                                            🥈
                                        @elseif($index === 2)
                                            🥉
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>

                                    {{-- Honorific Title --}}
                                    <td class="py-2 px-2 @if($index === 0) text-red-600 dark:text-red-400 @endif">
                                        {{ $honorificTitles[$index + 1] ?? '-' }}
                                    </td>

                                    {{-- Player Cell --}}
                                    <td class="py-2 px-2">
                                        <div class="flex items-center gap-2">
                                            @if(auth()->id() === $user->id)
                                                {{-- "YOU" badge --}}
                                                <span class="bg-indigo-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold animate-pulse">
                    YOU
                </span>
                                            @endif

                                            {{-- Avatar + Name --}}
                                            <img src="{{ asset($user->getPhotoURL()) }}" alt="{{ $user->name }}'s avatar"
                                                 class="w-8 h-8 rounded-full ring-2
                        {{ auth()->id() === $user->id
                            ? 'ring-indigo-500 dark:ring-indigo-400'
                            : 'ring-white dark:ring-gray-900' }}">
                                            <span class="{{ auth()->id() === $user->id ? 'text-indigo-700 dark:text-indigo-300 font-bold' : '' }}">
                {{ $user->name }}
            </span>
                                        </div>
                                    </td>
                                </tr>


                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 text-center text-gray-500 dark:text-gray-400">
                                        No participants found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="text-xs text-gray-500 dark:text-gray-400 italic">
                    XP and rankings are calculated from your activity on the platform — primarily through participating in gaming sessions and engaging with the community. Updates happen automatically as you earn more XP
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
