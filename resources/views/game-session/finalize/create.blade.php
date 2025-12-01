<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
            🎯 {{ __('Finalize Game Session') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    <!-- Info Section -->
                    <div
                        class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
                            🕹️ {{ $gameSession->name }}
                        </h3>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            This session took place on
                            <strong>{{ $gameSession->start_at->format('l, d M Y · H:i') }}</strong>.
                            Please record the final results and attendance for each confirmed participant below.
                        </p>
                    </div>
                    <x-input-error :messages="$errors->get('session')" class="mt-2"/>
                    <form action="{{ route('game-session.finalize.store', $gameSession->uuid) }}" method="POST">
                        @csrf
                        <!-- Participants Table -->
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-inner border border-gray-200 dark:border-gray-700">
                            <div class="p-5 overflow-x-auto">
                                <h3 class="text-sm uppercase tracking-wide text-gray-600 dark:text-gray-400 font-semibold mb-3">
                                    Participants
                                </h3>
                                <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
                                    <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                                        <th class="px-4 py-3 font-semibold">Photo</th>
                                        <th class="px-4 py-3 font-semibold">Name</th>
                                        <th class="px-4 py-3 font-semibold text-center">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($confirmedRegistrations as $registration)
                                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors">
                                            <td class="px-4 py-3">
                                                <img src="{{ asset($registration->user->getPhotoURL()) }}" alt="Avatar"
                                                     class="w-9 h-9 rounded-full object-cover ring-2 ring-gray-300 dark:ring-gray-700">
                                            </td>
                                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                                {{ $registration->user->name }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex justify-center gap-4">
                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                        <input type="radio" name="attendance[{{ $registration->id }}]"
                                                               value="participated"
                                                               class="text-green-600 focus:ring-green-500 dark:focus:ring-green-400">
                                                        <span class="text-green-700 dark:text-green-300 text-sm">Participated</span>
                                                    </label>
                                                    <label class="flex items-center gap-1 cursor-pointer">
                                                        <input type="radio" name="attendance[{{ $registration->id }}]"
                                                               value="absent"
                                                               class="text-red-600 focus:ring-red-500 dark:focus:ring-red-400">
                                                        <span
                                                            class="text-red-700 dark:text-red-300 text-sm">Absent</span>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <x-input-error :messages="$errors->get('attendance')" class="mt-2"/>
                            </div>
                        </div>

                        <!-- Final Result -->
                        <div
                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm uppercase tracking-wide text-gray-600 dark:text-gray-400 font-semibold mb-3">
                                Session Result
                            </h3>

                            <div class="flex flex-col sm:flex-row gap-4 sm:items-center">
                                <label class="text-gray-700 dark:text-gray-300 text-sm font-medium">
                                    Select Outcome:
                                </label>
                                <select name="result"
                                        class="p-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 focus:ring-2 focus:ring-indigo-500">
                                    <option value="">-- Select Result --</option>
                                    <option value="success" {{(old('result') == 'success' ? 'selected' : '')}}>✅
                                        Succeeded
                                    </option>
                                    <option value="fail" {{(old('result') == 'fail' ? 'selected' : '')}}>❌ Failed
                                    </option>
                                </select>
                                <x-input-error :messages="$errors->get('result')" class="mt-2"/>
                            </div>
                        </div>

                        <!-- Organizer Note -->
                        <div
                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm uppercase tracking-wide text-gray-600 dark:text-gray-400 font-semibold mb-3">
                                Organizer Note
                            </h3>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                Add a short public note or summary for this session (e.g., highlights, key takeaways, or
                                remarks about the group).
                            </p>

                            <textarea
                                name="note"
                                rows="3"
                                placeholder="Type your note here..."
                                class="w-full p-3 rounded-lg border border-gray-300 bg-white text-gray-800 placeholder-gray-500
                                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none
                                       dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:placeholder-gray-500"
                            >{{ old('note', $gameSession->note ?? '') }}</textarea>

                            @error('note')
                            <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Confirmation & Submit -->
                        <div class="pt-4 space-y-2 change-section">
                            <label class="flex items-start gap-3 p-4 rounded-lg border border-gray-200 dark:border-gray-700
                                      hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer">
                                <input type="checkbox" id="confirmFinalize" name="confirm_finalize" value="1"
                                       class="mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                                          dark:border-gray-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-900">
                                <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                <strong>Yes, I confirm I want to finalize this session.</strong><br>
                                Once saved, the session status and participant feedback cannot be changed.
                            </span>
                            </label>

                            <div class="hidden mt-4" id="finalizeButton">
                                <x-primary-button>
                                    ✅ Finalize Session
                                </x-primary-button>
                            </div>
                            @if (session('error'))
                                <x-input-error :messages="session('error')" class="mt-2"/>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // reveal button only when checkbox checked
        document.addEventListener('DOMContentLoaded', () => {
            const checkbox = document.getElementById('confirmFinalize');
            const button = document.getElementById('finalizeButton');
            checkbox.addEventListener('change', () => {
                button.classList.toggle('hidden', !checkbox.checked);
            });
        });
    </script>
</x-app-layout>
