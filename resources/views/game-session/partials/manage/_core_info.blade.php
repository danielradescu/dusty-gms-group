<!-- 🧩 Session Details Management -->
<div id="session-detail-management" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <form action="{{ route('game-session.manage.update.core-info', $gameSession->uuid) }}" method="POST"
              class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Section Header -->
            <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 flex items-center gap-2">
                ⚙️ Update Session Details
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Update the core information for this gaming session — including title, description, location, player limits, time, or game complexity.
                Please note that these details can only be modified <strong>before the session is confirmed</strong>.
                Once confirmed, all session information is locked and visible to participants as final.
            </p>

            <hr class="border-gray-200 dark:border-gray-700 my-4">

            <!-- Title -->
            <div>
                <x-input-label for="name" value="Session Title"/>
                <x-text-input id="name" name="name" type="text"
                              class="mt-1 block w-full"
                              :value="old('name', $gameSession->name)"
                              required autofocus/>
                <x-input-error :messages="$errors->get('name')" class="mt-2"/>
            </div>

            <!-- Description -->
            <div>
                <x-input-label for="description" value="Description"/>
                <textarea id="description" name="description" rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                           bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100
                           focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                          placeholder="Describe what games you’ll play, or any special notes for participants.">{{ old('description', $gameSession->description) }}</textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2"/>
            </div>

            <!-- Location -->
            <div>
                <x-input-label for="location" value="Location"/>
                <x-text-input id="location" name="location" type="text"
                              class="mt-1 block w-full"
                              :value="old('location', $gameSession->location)"
                              required/>
                <x-input-error :messages="$errors->get('location')" class="mt-2"/>
            </div>

            <!-- Date (fixed) + Time (editable) -->
            <div>
                <div class="grid grid-cols-2 gap-3 items-end">
                    <!-- Fixed Date -->
                    <div>
                        <x-input-label for="start_at_date" value="Date"/>
                        <x-text-input id="start_at_date" type="text"
                                      class="mt-1 block w-full bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 cursor-not-allowed"
                                      :value="$gameSession->start_at->format('Y-m-d (l)')"
                                      readonly/>
                    </div>

                    <!-- Editable Time -->
                    <div>
                        <x-input-label for="start_at_time" value="Adjust Time"/>
                        <input id="start_at_time"
                               name="start_at_time"
                               type="time"
                               value="{{ old('start_at_time', $gameSession->start_at->format('H:i')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                                      bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100
                                      focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                               required>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    You can adjust the hour and minute — the date stays the same.
                </p>
                <x-input-error :messages="$errors->get('start_at_time')" class="mt-2"/>
            </div>

            <!-- Player Count -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="min_players" value="Minimum Players"/>
                    <x-text-input id="min_players" name="min_players" type="number" min="1"
                                  class="mt-1 block w-full"
                                  :value="old('min_players', $gameSession->min_players)"
                                  required/>
                    <x-input-error :messages="$errors->get('min_players')" class="mt-2"/>
                </div>

                <div>
                    <x-input-label for="max_players" value="Maximum Players"/>
                    <x-text-input id="max_players" name="max_players" type="number" min="1"
                                  class="mt-1 block w-full"
                                  :value="old('max_players', $gameSession->max_players)"
                                  required/>
                    <x-input-error :messages="$errors->get('max_players')" class="mt-2"/>
                </div>
            </div>

            <!-- Complexity -->
            <div>
                <x-input-label for="complexity" value="Game Complexity"/>
                <select id="complexity" name="complexity"
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700
                               bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100
                               focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    @foreach(\App\Enums\GameComplexity::cases() as $complexity)
                        <option value="{{ $complexity->value }}"
                            @selected(old('complexity', $gameSession->complexity->value) === $complexity->value)>
                            {{ $complexity->label() }} — {{ $complexity->description() }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('complexity')" class="mt-2"/>
            </div>

            <!-- Confirmation Checkbox & Hidden Submit Button -->
            <div class="pt-4 space-y-2 change-section">
                <!-- Checkbox -->
                <label class="flex items-start gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700
                                hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer">
                    <input type="checkbox" name="confirm_core_info" value="1" class="change-confirm mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                                    dark:border-gray-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-900">
                    <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                            <strong>Yes, I want to make changes!</strong><br>
                            Selecting this will reveal the save button and allow you to update session details.
                        </span>
                </label>
                <x-input-error :messages="$errors->get('confirm_core_info')" class="mt-2" />

                <!-- Submit Button (hidden until checkbox checked) -->
                <div class="save-button hidden mt-4">
                    <x-button variant="primary">💾 Save Session Details</x-button>
                </div>
                @if (session('coreInfoSaved'))
                    <span
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 5000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Saved.') }}</span>
                @endif
            </div>
        </form>
    </div>
</div>
