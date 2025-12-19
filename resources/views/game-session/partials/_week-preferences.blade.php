<div id="week-session-preferences" class="py-10">
    <div class="mb-3">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                This Week Session Request
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
            @if($gameSessionRequests->count())
                <div class="space-y-2">
                    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">
                        ✅ You’ve requested game sessions for:
                    </p>

                    <ul class="space-y-1">
                        @foreach($gameSessionRequests->sortBy('preferred_time') as $req)
                            <li class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50
                               border border-gray-200 dark:border-gray-700 rounded-md px-3 py-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="w-20 text-indigo-600 dark:text-indigo-400 font-semibold">
                                        {{ $req->preferred_time->format('l') }}
                                    </span>
                                    <span class="text-gray-600 dark:text-gray-300">
                                        {{ $req->preferred_time->format('d F') }}
                                    </span>
                                </div>

                                @if($req->auto_join)
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-medium text-green-700 dark:text-green-300">
                                        ⚡Auto-join
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 dark:text-amber-300">
                                        🔔Notify only
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                🎯 You have no sessions requested yet — ready to roll? Pick your ideal play day!
            @endif
        </div>
    </div>

    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Pick the days you're interested in.</h3>
    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">The organizer selects the initial start time. You can later request a change or communicate your planned arrival time.</p>
    <form action="{{ route('game-session-request.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Per-Day Cards -->
            @foreach($slots as $slot)
                @php
                    $disabled = false;
                    $name = 'requests['.$slot['dt']?->toDateString().']';
                @endphp
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5 shadow-sm hover:shadow-md transition">
                    <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3 flex items-center justify-between">
                        <span>{{ $slot['label'] }}</span>
                        @if($disabled)
                            <span class="text-xs text-gray-400">(passed)</span>
                        @endif
                    </h4>

                    <div class="space-y-2">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $name }}" value="auto"
                                   class="accent-indigo-600" {{ $slot['value'] == "auto" ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}>
                            <span class="text-sm text-gray-700 dark:text-gray-300">🟢 Join & Notify</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $name }}" value="notify"
                                   class="accent-indigo-600" {{ $slot['value'] == "notify" ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}>
                            <span class="text-sm text-gray-700 dark:text-gray-300">🔔 Notify Only</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="radio" name="{{ $name }}" value=""
                                   class="accent-indigo-600" {{ $slot['value'] == "" ? 'checked' : '' }} {{ $disabled ? 'disabled' : '' }}>
                            <span class="text-sm text-gray-500 dark:text-gray-400">🚫 Not Available</span>
                        </label>
                    </div>

                    <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                        👥 {{ $slot['total_interested'] ?? 0 }}
                        <span class="text-gray-400 dark:text-gray-500">({{ $slot['auto_joiners'] ?? 0 }} auto-join)</span>
                    </p>
                </div>
            @endforeach
            @if (! auth()->user()->notifications_disabled)
                <!-- Global Notifications Card -->
                <div class="rounded-xl border border-indigo-200 dark:border-indigo-700 bg-indigo-50 dark:bg-indigo-900/20 p-5 shadow-sm hover:shadow-md transition">
                    <h4 class="text-base font-semibold text-indigo-700 dark:text-indigo-300 mb-3 flex items-center gap-2">
                        🌍 Any Day Notifications
                    </h4>

                    <p class="text-sm text-indigo-800 dark:text-indigo-200 mb-4">
                        Get a delayed notification about <strong>any new session</strong> — even if you haven’t selected a specific day yet.
                    </p>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="notify_all_days" value="1" class="accent-indigo-600"
                            {{ old('notify_all_days', $notifyAllDays ?? false) ? 'checked' : '' }}>
                        <span class="text-sm text-indigo-800 dark:text-indigo-200">🔔 Enable All-Session Notifications</span>
                    </label>
                </div>
            @endif
        </div>

        <div class="flex flex-col items-center md:items-start text-center md:text-left pt-2 gap-3">
            <x-button class="w-full md:w-1/5 min-w-[10rem]" variant="primary">
                💾 Save Preferences
            </x-button>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                You can change these preferences anytime.
            </div>
        </div>

    </form>

    <!-- Info Section -->

    <h3 class="mt-5 text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">
        Understanding Your Notification Settings:
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm leading-relaxed text-gray-700 dark:text-gray-300">

        <!-- Day Preferences -->
        <div class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">
                Day Preferences
            </h4>
            <p>
                🟢 <b>Join &amp; Notify</b>: You’ll be auto-joined for the first session created this day that has
                <b>Casual</b> or <b>Flexible</b> complexity. You’ll still receive notifications for
                <b>Competitive</b> sessions or any other session created this day.
            </p>
            <br>
            <p>
                🔔 <b>Notify Only</b>: You’ll receive an <b>instant notification</b> whenever a new session
                is created for this day, inviting you to join.
            </p>
            <br>
            <p>
                🚫 <b>Not Available</b>: This will <b>reset</b> any of your existing preferences for this day —
                you won’t receive auto-joins or notifications until you change it again.
            </p>
            <br>
            <p>
                After joining a session, your setting will automatically switch to <b>Notify Only</b>.
                Users who have selected a day will always receive notifications first, giving them early access
                to join or auto-join new sessions.
            </p>
        </div>
        @if (! auth()->user()->notifications_disabled)
            <!-- Any Day Notifications -->
            <div class="bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800 rounded-lg p-4 shadow-sm">
                <h4 class="font-semibold text-indigo-800 dark:text-indigo-300 mb-2">
                    Any Day Notifications
                </h4>
                <p>
                    🔔 <b>Any Day Notifications</b>: You’ll receive alerts for <b>any new game session</b> — even if you didn’t select a specific day.
                </p>
                <br>
                <p>
                    ⏱️ These notifications are <b>delayed by about 2 hours</b> after the day-specific notifications are sent.
                    This encourages players to <b>vote for specific days</b>, helping organizers plan better.
                </p>
                <br>
                <p>
                    You can enable this as a general backup to make sure you never miss a new session announcement.
                </p>
            </div>
         @endif
    </div>
</div>
