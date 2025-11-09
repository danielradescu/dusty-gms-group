<div x-data="{ open:false }" class="py-10">
    <!-- Header / status row shown above -->
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
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold">
                                        {{ $req->preferred_time->format('l') }}
                                    </span>
                                    <span class="text-gray-600 dark:text-gray-300">
                                        {{ $req->preferred_time->format('M d · H:i') }}
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

        <div class="mt-3">
            <button @click="open = !open"
                    class="float-right px-3 py-1 text-sm rounded-md text-white bg-indigo-600 hover:bg-indigo-500">
                <span x-text="open ? 'Close' : '{{$gameSessionRequests->count() ? 'Update requests' : 'Request a session'}}'"></span>
            </button>
        </div>
    </div>
    @if ($errors->any())
        <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200">
            <div class="flex">
                <div class="shrink-0">
                    <!-- optional icon -->
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3 text-sm text-red-700">
                    <p class="font-semibold">Please fix the following issues:</p>
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    <!-- Form -->
    <div x-show="open" x-transition
         class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 p-5">
        <form action="{{ route('game-session-request.store') }}" method="POST" class="space-y-4">
            @csrf
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Pick the days you’re interested in. For each day, choose whether you want to <b>auto-join</b> a session
                if it’s created,
                or <b>only receive a notification</b>.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($slots as $slot)
                    @php
                        $disabled = !$slot['dt'] || $slot['dt']->isPast();
                        $name = 'requests['.$slot['dt']?->toDateTimeString().']'; // e.g. requests[2025-11-08 18:00:00]
                    @endphp

                    <fieldset class="rounded-md border border-gray-200 dark:border-gray-700 p-4
                                    {{ $disabled ? 'opacity-60' : '' }}">
                        <legend
                            class="flex items-center justify-between text-sm font-semibold text-gray-900 dark:text-gray-100">
                            <span>
                                {{ $slot['label'] }}
                                @if($disabled)
                                    <span class="ml-1 text-xs text-gray-500">(passed)</span>
                                @endif
                            </span>
                        </legend>

                        <div class="mt-3 space-y-2">
                            <!-- Auto-join -->
                            <label class="flex items-start gap-2">
                                <input type="radio"
                                       name="{{ $name }}"
                                       value="auto"
                                       class="mt-1 accent-indigo-600"
                                    {{ $disabled ? 'disabled' : '' }}
                                {{$slot['value'] == "auto" ? 'checked' : ''}}>
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    <b>Auto-join</b> if a session is created for this slot.
                                    You’ll also receive an email notification.
                                </span>
                            </label>

                            <!-- Only notify -->
                            <label class="flex items-start gap-2">
                                <input type="radio"
                                       name="{{ $name }}"
                                       value="notify"
                                       class="mt-1 accent-indigo-600"
                                    {{ $disabled ? 'disabled' : '' }}
                                    {{$slot['value'] == "notify" ? 'checked' : ''}}>
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    <b>Only notify me</b> when a session is created for this slot.
                                </span>
                            </label>

                            <!-- None (clear selection) -->
                            <label class="flex items-start gap-2">
                                <input type="radio"
                                       name="{{ $name }}"
                                       value=""
                                       class="mt-1 accent-indigo-600"
                                    {{ $disabled ? 'disabled' : '' }}
                                    {{$slot['value'] == "" ? 'checked' : ''}}>
                                <span class="text-sm text-gray-500">
                                    Not available this day.
                                </span>
                            </label>
                        </div>
                        <!-- Counter -->
                        <div class="mt-5 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-3">
                            <span>👥 {{ $slot['total_interested'] ?? 0 }} total interested</span>
                            <span>⚡ {{ $slot['auto_joiners'] ?? 0 }} auto-joiners</span>
                        </div>
                    </fieldset>
                @endforeach
            </div>

            <div class="flex items-center justify-between pt-2">
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    You can change these preferences anytime.
                </div>
                <button type="submit"
                        class="px-4 py-2 rounded-md text-white bg-indigo-600 hover:bg-indigo-500">
                    Save Preferences
                </button>
            </div>
        </form>
    </div>
</div>
