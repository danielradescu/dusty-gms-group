<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            🎲 {{ __('Create New Game Session') }}
        </h2>
    </x-slot>

<div class="py-12 pb-0">
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 mt-1">
{{--        @include('partials._errors')--}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <form action="{{ route('game-session.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" value="Session Name"/>
                        <x-text-input id="name" name="name" type="text"
                                      class="mt-1 block w-full"
                                      :value="old('name')" required autofocus/>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                    </div>

                    <!-- Description -->
                    <div>
                        <x-input-label for="description" value="Description"/>
                        <textarea id="description" name="description" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                           bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100
                                           focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2"/>
                    </div>

                    <!-- Location -->
                    <div>
                        <x-input-label for="location" value="Location"/>
                        <x-text-input id="location" name="location" type="text"
                                      class="mt-1 block w-full"
                                      :value="old('location')" required/>
                        <x-input-error :messages="$errors->get('location')" class="mt-2"/>
                    </div>

                    <!-- Interest Overview -->
                    <div
                        class="mb-6 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-3">
                            🧩 Interest Overview (This Week)
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach($interestStats as $slot)
                                <div
                                    class="flex items-center justify-between bg-white dark:bg-gray-900/40 rounded-md px-3 py-2 shadow-sm">
                                    <div class="text-sm text-gray-700 dark:text-gray-300">
                                        <span class="font-medium">{{ $slot['label'] }}</span>
                                        <br>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $slot['time']->format('M d, H:i') }}
                                            </span>
                                    </div>

                                    <span class="inline-flex items-center justify-center text-sm font-semibold rounded-full
                                                     px-2.5 py-1
                                                     {{ $slot['count'] > 4
                                                        ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200'
                                                        : ($slot['count'] > 0
                                                            ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100'
                                                            : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300') }}">
                                            👥 {{ $slot['count'] }}
                                        </span>
                                </div>
                            @endforeach
                        </div>

                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                            * Number of users who have shown interest for each time slot this week.
                        </p>
                    </div>

                    <!-- Start Date & Time -->
                    <label for="start_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Start Time
                    </label>
                    <input
                        id="start_at"
                        name="start_at"
                        type="datetime-local"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        required
                        value="{{old('start_at')}}"
                        onfocus="this.showPicker && this.showPicker()" {{-- ✅ triggers calendar open if supported --}}
                    >
                    <x-input-error :messages="$errors->get('start_at')" class="mt-2"/>
                    <!-- Player Counts -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="min_players" value="Minimum Players"/>
                            <x-text-input id="min_players" name="min_players" type="number" min="1"
                                          class="mt-1 block w-full"
                                          :value="old('min_players', 3)" required/>
                            <x-input-error :messages="$errors->get('min_players')" class="mt-2"/>
                        </div>

                        <div>
                            <x-input-label for="max_players" value="Maximum Players"/>
                            <x-text-input id="max_players" name="max_players" type="number" min="1"
                                          class="mt-1 block w-full"
                                          :value="old('max_players', 10)" required/>
                            <x-input-error :messages="$errors->get('max_players')" class="mt-2"/>
                        </div>
                    </div>

                    <!-- Complexity -->
                    <select id="complexity" name="complexity"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                    dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @foreach($complexities as $complexity)
                            <option value="{{ $complexity->value }}"
                                {{ old('complexity') == $complexity->value ? 'selected' : '' }}>
                                {{ $complexity->label() }} — {{ $complexity->description() }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('complexity')" class="mt-2"/>

                    <!-- Delay option -->
                    <div class="mt-6">
                        <label class="flex items-start gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700
                                      hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer">
                            <input type="checkbox" name="delay_publication" value="1"
                                   class="mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                                          dark:border-gray-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-900">
                            <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    <strong>Delay making the session public for 6 hours</strong><br>
                                    I want to share it privately with a few friends first.
                                    I’ll send them the link myself before it becomes visible to everyone.
                                </span>
                        </label>
                    </div>

                    <!-- Organizer (admins only) -->
                    @if(auth()->user()->hasAdminPermission())
                        <div>
                            <x-input-label for="organized_by" value="Organizer"/>
                            <select id="organized_by" name="organized_by"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                               dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-You-</option>
                                @foreach($organizers as $organizer)
                                    <option value="{{ $organizer->id }}"
                                        @php
                                            if (old('organized_by')) {
                                                echo old('organized_by') == $organizer->id ? 'selected' : '';
                                            } else {
                                                echo auth()->user()->id == $organizer->id ? 'selected' : '';
                                            }
                                        @endphp
                                    >
                                        {{ $organizer->name }} - {{$organizer->role->label()}}

                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('organized_by')" class="mt-2"/>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <x-button variant="primary">💾 Create Session</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
