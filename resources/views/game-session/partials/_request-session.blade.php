<div id="week-session-request" class="py-10">
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
    @include('partials._errors')
    <!-- Form -->

        <form action="{{ route('game-session-request.store') }}" method="POST" class="space-y-4">
            @csrf
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Pick the days you're interested in.
                For each day, choose whether you want to <strong>auto-join</strong> a session if it’s created, or <strong>only receive a notification</strong> about it.
                <br/>
                <strong>Note:</strong> The organizer selects the initial start time. You can later request a change or communicate your planned arrival time.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($slots as $slot)
                    @php
                        $disabled = false;//!$slot['dt'] || $slot['dt']->isPast();
                        $name = 'requests['.$slot['dt']?->toDateString().']';
                    @endphp

                    <fieldset class="rounded-md border border-gray-200 dark:border-gray-700 p-4
                                    {{ $disabled ? 'opacity-60' : '' }}">
                        <legend
                            class="flex items-center justify-between text-sm font-semibold text-gray-900 dark:text-gray-100">
                            <span>
                                {{  $slot['label'] }}
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
                                    <b>Auto-join and notify</b> the first game session that is created for this day.
                                    You’ll also receive a notifications about the other game session created in this day.
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
                                    <b>Notify me</b> every time a session is created this day.
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
                        <span class="mt-3 inline-block">
                            👥 {{ $slot['total_interested'] ?? 0 }}
                            <span class="text-gray-400 dark:text-gray-500">
                                ({{ $slot['auto_joiners'] ?? 0 }} auto-join)
                            </span>
                        </span>
                    </fieldset>
                @endforeach
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

</div>
