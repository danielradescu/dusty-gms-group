<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🎲 Community Collection
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-xl p-6 space-y-6">

                @if (session('success'))
                    <div class="bg-emerald-100 dark:bg-emerald-800/30 border border-emerald-200 dark:border-emerald-700
                                text-emerald-700 dark:text-emerald-300 rounded-md p-3 text-sm mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 dark:bg-red-800/30 border border-red-200 dark:border-red-700
                                text-red-700 dark:text-red-300 rounded-md p-3 text-sm mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- ========================= -->
                <!-- BGG Profile + Filters -->
                <!-- ========================= -->
                    @include('partials._errors')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- 🧩 BGG Profile Section -->
                        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-inner flex flex-col gap-3">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                BoardGameGeek Profile
                            </h3>

                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                Enter your BGG username to automatically import your collection.
                            </p>

                            <form method="POST" action="{{ route('bgg.profile.save') }}" class="flex flex-col sm:flex-row gap-2">
                                @csrf
                                <input
                                    type="text"
                                    name="bgg_username"
                                    placeholder="e.g. meeple_master"
                                    value="{{ old('bgg_username', $user->bgg_username ?? '') }}"
                                    class="flex-1 rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800
                       text-sm text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                                >
                                <x-button type="submit" variant="primary" class="!w-auto">
                                    💾 Save
                                </x-button>
                            </form>

                            {{-- 💡 Collection Link Generator --}}
                            @if(!empty($user->bgg_username))
                                @php
                                    $collectionLink = "https://boardgamegeek.com/collection/user/{$user->bgg_username}?gallery=small&subtype=boardgame&own=1&ff=1";
                                @endphp

                                <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">
                                        You can view or share your BGG collection link below. This link shows only owned board games.
                                    </p>

                                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full">
                                        <input
                                            id="bggCollectionLink"
                                            type="text"
                                            readonly
                                            value="{{ $collectionLink }}"
                                            class="flex-1 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-700
                                                bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 text-sm w-full"
                                        >

                                        {{-- 📋 Copy Button --}}
                                        <x-button
                                            x-data
                                            @click="navigator.clipboard.writeText(document.getElementById('bggCollectionLink').value);
                                                    $dispatch('notify', { message: 'BGG collection link copied!' })"
                                            variant="secondary"
                                            :disableOnClick="false"
                                            class="sm:!w-auto">
                                            Copy
                                        </x-button>

                                        {{-- 📤 Share Button --}}
                                        <x-button
                                            x-data
                                            @click="
                                                const link = document.getElementById('bggCollectionLink').value;
                                                if (navigator.share) {
                                                    navigator.share({
                                                        title: 'My BoardGameGeek Collection',
                                                        text: 'Check out my BGG board game collection!',
                                                        url: link
                                                    }).catch(() => {});
                                                } else {
                                                    navigator.clipboard.writeText(link);
                                                    $dispatch('notify', { message: 'Link copied (sharing not supported)' });
                                                }
                                            "
                                            variant="primary"
                                            :disableOnClick="false"
                                            class="sm:!w-auto">
                                            Share
                                        </x-button>
                                    </div>



                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        🔁 You can re-submit your BGG username to manually trigger a sync (once per day),
                                        or just wait for the automatic weekly update.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- 🎯 Filter Section -->
                        <div class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-inner flex flex-col gap-3">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                Filter Games
                            </h3>

                            <form method="GET" action="{{ route('boardgames.index') }}" class="flex flex-col gap-3">

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                    {{-- Search --}}
                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Search by name..."
                                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800
                                            text-sm text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                                            >

                                    {{-- Number of Players --}}
                                    <select name="players"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800
                                                text-sm text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All players</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" @selected(request('players') == $i)>{{ $i }} players</option>
                                        @endfor
                                    </select>

                                    {{-- Filter by user --}}
                                    <select name="user" id="user"
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800
                                                text-sm text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">All users</option>
                                        @foreach($usersWithGames as $u)
                                            <option value="{{ $u->bgg_username }}" @selected(request('user') == $u->bgg_username)>
                                                {{ $u->name }} ({{ $u->bgg_username }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 🧩 Include expansions --}}
                                <div class="flex items-center mt-1">
                                    <input
                                        type="checkbox"
                                        id="include_expansions"
                                        name="include_expansions"
                                        value="1"
                                        {{ request('include_expansions') ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                    >
                                    <label for="include_expansions" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        Include expansions
                                    </label>
                                </div>

                                {{-- 🔘 Buttons --}}
                                <div class="flex justify-end gap-2 mt-2">
                                    {{-- Clear Filters --}}
                                    <a href="{{ route('boardgames.index') }}"
                                       class="inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-gray-700
                                          bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-sm px-3 py-2
                                          hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                        ✖ Clear
                                    </a>

                                    {{-- Apply Filters --}}
                                    <x-button type="submit" variant="primary" class="!w-auto">
                                        🔍 Apply
                                    </x-button>
                                </div>
                            </form>
                        </div>



                    </div>


                    <!-- ========================= -->
                <!-- Boardgames List -->
                <!-- ========================= -->
                @if($boardgames->isEmpty())
                    <p class="text-gray-600 dark:text-gray-400 text-center">
                        No games have been added yet. Be the first to add your collection! 🌟
                    </p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($boardgames as $game)
                            <a href="{{ $game->bgg_url }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="relative group flex flex-col bg-gray-50 dark:bg-gray-900 rounded-xl overflow-hidden shadow-md
              hover:shadow-lg hover:-translate-y-1 transition-all duration-200 focus:outline-none
              focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">

                                {{-- Thumbnail --}}
                                <div class="relative p-5 bg-gray-100 dark:bg-gray-700 transition-colors duration-200">
                                    <img src="{{ $game->thumbnail ?? '/images/placeholder-boardgame.jpg' }}"
                                         alt="{{ $game->name }}"
                                         class="w-full h-48 object-contain transition-transform duration-200 group-hover:scale-105 group-focus:scale-105">

                                    @if($game->is_expansion)
                                        <span class="absolute top-2 left-2 bg-indigo-600 text-white text-xs px-2 py-1 rounded-md shadow">
                    Expansion
                </span>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <div class="p-4 flex flex-col flex-1 justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                            {{ $game->name }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            📅 {{ $game->year_published ?? 'N/A' }} · 👥 {{ $game->min_players }}
                                            –{{ $game->max_players }}
                                        </p>
                                        @if($game->is_ranked)
                                            <p class="text-sm text-amber-600 dark:text-amber-400 mt-1">
                                                🏅 Rank #{{ $game->rank_boardgame }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Owners --}}
                                    <div class="mt-4">
                                        <p class="text-xs uppercase text-gray-500 dark:text-gray-400 mb-2">
                                            Owned by:
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            @forelse($game->users as $user)
                                                <div class="relative group/owner inline-block">
                                                    <img src="{{ asset($user->getPhotoURL()) }}"
                                                         class="w-12 h-12 rounded-full ring-2 ring-black dark:ring-black
                                                        object-cover transition-transform duration-200 group-hover:scale-105
                                                        shadow-[0_0_15px_rgba(0,0,0,0.8)] dark:shadow-[0_0_20px_rgba(0,0,0,1)]"
                                                         alt="{{ $user->name }}">
                                                    <span class="absolute bottom-0 left-0 right-0 text-[9px] text-white bg-black/60
                                                                 rounded-b-full py-0.5 font-medium text-center leading-tight">
                                                        {{ $user->shortName ?? $user->name }}
                                                    </span>
                                                </div>
                                            @empty
                                                <span class="text-xs text-gray-400">—</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                {{-- Click hint label --}}
                                <div class="absolute bottom-2 right-3 text-[10px] text-gray-400
                    group-hover:text-indigo-500 dark:group-hover:text-indigo-400 transition-colors">
                                    ⧉ Click to open on BGG
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-6">
                        {{ $boardgames->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
