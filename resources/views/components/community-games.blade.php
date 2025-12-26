@if ($games->count())
    <section class="w-full py-20 bg-gray-50 dark:bg-gray-800 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 text-center">
            <h2 class="text-3xl font-semibold mb-10">{{ __('landing.community_games.title') }}</h2>

            {{-- Scrollable container --}}
            <div
                x-data
                @wheel.prevent="$el.scrollLeft += $event.deltaY"
                class="relative overflow-x-auto no-scrollbar"
            >
                <div class="flex gap-6 px-6 sm:px-10 py-6 min-w-max snap-x snap-mandatory">
                    {{-- Games --}}
                    @forelse($games as $game)
                        <a href="{{ $game->bgg_url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="relative snap-center shrink-0 w-48 sm:w-56 bg-white dark:bg-gray-900
                                  border border-gray-200 dark:border-gray-700 rounded-xl shadow-md
                                  hover:shadow-xl hover:z-20 transform hover:scale-105 transition-all duration-300
                                  flex flex-col items-center overflow-hidden text-center group"
                        >
                            {{-- Thumbnail --}}
                            <div class="relative bg-gray-100 dark:bg-gray-700 flex items-center justify-center h-56 overflow-hidden">
                                <div class="w-full h-full flex items-center justify-center p-4">
                                    <img src="{{ $game->thumbnail ?? '/images/placeholder-boardgame.jpg' }}"
                                         alt="{{ $game->name }}"
                                         class="max-h-48 max-w-full object-contain transition-transform duration-300 group-hover:scale-105"
                                         loading="lazy">
                                </div>
                            </div>

                            {{-- Game name --}}
                            <div class="px-3 py-3 w-full">
                                <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100
                                           group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors truncate">
                                    {{ $game->name }}
                                </h3>

                                <p class="text-[10px] sm:text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    {{ __('landing.community_games.open_bgg') }}
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="w-full py-10 text-gray-500 dark:text-gray-400 text-center">
                            {{ __('landing.community_games.empty') }}
                        </div>
                    @endforelse

                    {{-- “Join Us” Card --}}
                    <a href="{{ route('public-join-create') }}"
                       class="relative snap-center shrink-0 w-48 sm:w-56 bg-indigo-50 dark:bg-indigo-900/30
                              border-2 border-dashed border-indigo-400 dark:border-indigo-600 rounded-xl
                              shadow-inner hover:shadow-lg hover:z-20 transform hover:scale-105
                              transition-all duration-300 flex flex-col items-center justify-center text-center group"
                    >
                        <div class="flex flex-col items-center justify-center px-4 py-10">
                            <div class="text-5xl text-indigo-500 dark:text-indigo-400 mb-2">🎯</div>
                            <h3 class="text-base font-semibold text-indigo-600 dark:text-indigo-400 mb-1">
                                {{ __('landing.community_games.join_title') }}
                            </h3>
                            <p class="text-xs text-gray-600 dark:text-gray-300">
                                {{ __('landing.community_games.join_text') }}
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-6">
                {{ __('landing.community_games.scroll_hint') }}
            </p>
        </div>
    </section>
@endif
