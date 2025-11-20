<div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 shadow-inner border border-gray-200 dark:border-gray-700">
    <h3 class="text-sm uppercase tracking-wide text-gray-500 dark:text-gray-400 font-semibold mb-3">
        Organizer Management
    </h3>

    <form method="POST" action="#" class="space-y-4">
        @csrf
        <div>
            <label for="new_organizer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Select a new organizer:
            </label>
            <select id="new_organizer" name="new_organizer_id"
                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200
                               focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                <option value="">— Choose a participant —</option>
                @foreach($confirmedRegistrations as $registration)
                    @if ($registration->user->id !== $gameSession->organized_by)
                        <option value="{{ $registration->user->id }}">{{ $registration->user->name }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <!-- Confirmation Checkbox & Hidden Submit Button -->
        <div class="pt-4 space-y-2 change-section">
            <!-- Checkbox -->
            <label class="flex items-start gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700
                                hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer">
                <input type="checkbox" class="change-confirm mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                                    dark:border-gray-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-900">
                <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                            <strong>I have already spoken with the person who will take over as organizer, and they agreed to do so.</strong><br>
                            Checking this box will confirm your decision and reveal the button to assign the new organizer.
                        </span>
            </label>

            <!-- Submit Button (hidden until checkbox checked) -->
            <div class="save-button hidden mt-4">
                <x-primary-button>
                    🔁 Change Organizer
                </x-primary-button>
            </div>
        </div>
    </form>
</div>
