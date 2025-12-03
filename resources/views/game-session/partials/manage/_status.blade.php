<!-- 🧩 Organizer Session Management Section -->
<div id="status-session-management"
     class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 shadow-inner border border-gray-200 dark:border-gray-700 mt-8">
    <h3 class="text-sm uppercase tracking-wide text-gray-500 dark:text-gray-400 font-semibold mb-3">
        Session Status Management
    </h3>

    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
        Here you can update the status of this game session based on community responses:
        <br><br>
        <span class="block text-sm text-gray-600 dark:text-gray-400">
            • <strong>Confirm —</strong> This session will take place and all confirmed participants will be notified.
                I acknowledge that no further changes can be made after confirmation, and I’ve secured a location with participants agreeing on date and time.
                I can confirm that enough people have committed to participating.
                <br/>
            • <strong>Cancel —</strong> This session cannot happen. All confirmed or interested participants will be notified
                (e.g., not enough players, organizer unavailable, or no location available).
            </span>
    </p>

    <form action="{{ route('game-session.manage.update.status', $gameSession->uuid) }}" method="POST" class="space-y-5">
        @csrf
        @method('PATCH')

        <!-- Session status options -->
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Choose session status:
            </label>
            <select id="status" name="status"
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200
                           focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                <option value="">— Select an action —</option>

                <option
                    {{old('status', $gameSession->status->value) == \App\Enums\GameSessionStatus::CONFIRMED_BY_ORGANIZER->value ? "SELECTED" : ""}}
                    value="{{\App\Enums\GameSessionStatus::CONFIRMED_BY_ORGANIZER->value}}"
                >✅ Confirm Session
                </option>

                <option
                    {{old('status', $gameSession->status->value) == \App\Enums\GameSessionStatus::CANCELLED->value ? "SELECTED" : ""}}
                    value="{{\App\Enums\GameSessionStatus::CANCELLED->value}}"
                >🚫 Cancel Session
                </option>
            </select>
        </div>
        <x-input-error :messages="$errors->get('status')" class="mt-2"/>

        <!-- Cancellation reason (only visible when "cancel" is selected) -->
        <div id="cancel-reason-container" class="hidden">
            <label for="cancel_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Cancellation reason (required)
            </label>
            <textarea id="cancel_reason" name="cancel_reason" rows="3"
                      placeholder="e.g., Not enough confirmed players, organizer unavailable..."
                      class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200
                             focus:ring-rose-500 focus:border-rose-500 text-sm placeholder-gray-400">{{old('cancel_reason', $gameSession->note)}}</textarea>
        </div>
        <x-input-error :messages="$errors->get('cancel_reason')" class="mt-2"/>

        <!-- Confirmation Checkbox & Hidden Submit Button -->
        <div class="pt-4 space-y-2 change-section">
            <!-- Checkbox -->
            <label class="flex items-start gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700
                                hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer">
                <input type="checkbox" name="confirm_status" value="1" class="change-confirm mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                <input type=" checkbox" name="confirm_status" value="1" class="change-confirm mt-1 h-5 w-5
                text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                dark:border-gray-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-900">
                <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    <strong>I have enough information to decide the outcome of this session.</strong><br>
                    Selecting this option will unlock the <em>Save</em> button, allowing you to confirm or cancel the session as needed.
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        <li>
                            <span class="text-gray-600 dark:text-gray-400">
                                <strong>Confirming</strong> the session will notify
                                <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ $confirmedNotifyCount }}
                                </span>
                                other confirmed participant{{ $confirmedNotifyCount !== 1 ? 's' : '' }}.
                            </span>
                        </li>
                        <li>
                            <span class="text-gray-600 dark:text-gray-400">
                                <strong>Cancelling</strong> the session will notify
                                <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ $confirmedNotifyCount }}
                                </span>
                                other confirmed and
                                <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                                    {{ $interestedNotifyCount }}
                                </span>
                                interested participant{{ $interestedNotifyCount !== 1 ? 's' : '' }}.
                            </span>
                        </li>
                    </ul>
                </span>

            </label>
            <x-input-error :messages="$errors->get('confirm_status')" class="mt-2"/>

            <!-- Submit Button (hidden until checkbox checked) -->
            <div class="save-button hidden mt-4">
                <x-button variant="primary">💾 Save Session Status</x-button>
            </div>
            @if (session('statusSaved'))
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
