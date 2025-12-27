<div id="week-session-preferences" class="py-10">
    <div class="mb-3">
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                {{ __('dashboard.this_week_title') }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400">
            @if($gameSessionRequests->count())
                <div class="space-y-2">
                    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">
                        {{ __('dashboard.requested_sessions_intro') }}
                    </p>

                    <ul class="space-y-1">
                        @foreach($gameSessionRequests->sortBy('preferred_time') as $req)
                            <li class="flex items-center justify-between bg-gray-50 dark:bg-gray-800/50
                               border border-gray-200 dark:border-gray-700 rounded-md px-3 py-2 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="w-20 text-indigo-600 dark:text-indigo-400 font-semibold">
                                        {{ $req->preferred_time->translatedFormat('l') }}
                                    </span>
                                    <span class="text-gray-600 dark:text-gray-300">
                                        {{ $req->preferred_time->translatedFormat('d F') }}
                                    </span>
                                </div>

                                @if($req->auto_join)
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-medium text-green-700 dark:text-green-300">
                                        {{ __('dashboard.auto_join') }}
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 dark:text-amber-300">
                                        {{ __('dashboard.notify_only') }}
                                    </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                {{ __('dashboard.no_requests') }}
            @endif
        </div>
    </div>

    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('dashboard.pick_days_title') }}</h3>
    <p class="text-sm text-gray-600 dark:text-gray-300 font-medium">{{ __('dashboard.pick_days_subtitle') }}</p>
    <form action="{{ route('game-session-request.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
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

                    @php
                        $total = $slot['total_interested'] ?? 0;
                        $auto = $slot['auto_joiners'] ?? 0;

                        $textColor = match ($slot['value']) {
                            'auto' => 'text-green-600 dark:text-green-400',
                            'notify' => 'text-yellow-600 dark:text-yellow-400',
                            default => 'text-gray-400 dark:text-gray-500',
                        };

                        $displayTotal = $total > 0 ? $total : '-';
                    @endphp

                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="{{ $name }}" value="auto"
                                       class="accent-indigo-600"
                                    {{ $slot['value'] == 'auto' ? 'checked' : '' }}
                                    {{ $disabled ? 'disabled' : '' }}>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('dashboard.join_and_notify') }}</span>
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="radio" name="{{ $name }}" value="notify"
                                       class="accent-indigo-600"
                                    {{ $slot['value'] == 'notify' ? 'checked' : '' }}
                                    {{ $disabled ? 'disabled' : '' }}>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('dashboard.notify_only_label') }}</span>
                            </label>

                            <label class="flex items-center gap-2">
                                <input type="radio" name="{{ $name }}" value=""
                                       class="accent-indigo-600"
                                    {{ $slot['value'] == '' ? 'checked' : '' }}
                                    {{ $disabled ? 'disabled' : '' }}>
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('dashboard.not_available_label') }}</span>
                            </label>
                        </div>

                        <div class="flex flex-col justify-center min-w-[4rem] text-center">
                            <span class="text-5xl font-bold leading-none {{ $textColor }}">
                                {{ $displayTotal }}
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                {{ __('dashboard.auto_join_label', ['count' => $auto]) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach

            @if (! auth()->user()->notifications_disabled)
                <div class="rounded-xl border border-indigo-200 dark:border-indigo-700 bg-indigo-50 dark:bg-indigo-900/20 p-5 shadow-sm hover:shadow-md transition">
                    <h4 class="text-base font-semibold text-indigo-700 dark:text-indigo-300 mb-3 flex items-center gap-2">
                        {{ __('dashboard.any_day_title') }}
                    </h4>

                    <p class="text-sm text-indigo-800 dark:text-indigo-200 mb-4">
                        {!! __('dashboard.any_day_description') !!}
                    </p>

                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="notify_all_days" value="1" class="accent-indigo-600"
                            {{ old('notify_all_days', $notifyAllDays ?? false) ? 'checked' : '' }}>
                        <span class="text-sm text-indigo-800 dark:text-indigo-200">{{ __('dashboard.any_day_enable') }}</span>
                    </label>
                </div>
            @endif
        </div>

        <div class="flex flex-col items-center md:items-start text-center md:text-left pt-2 gap-3">
            <x-button class="w-full md:w-1/5 min-w-[10rem]" variant="primary">
                {{ __('dashboard.save_preferences') }}
            </x-button>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                {{ __('dashboard.preferences_hint') }}
            </div>
        </div>
    </form>

    <h3 class="mt-5 text-base font-semibold text-gray-800 dark:text-gray-100 mb-4">
        {{ __('dashboard.understanding_title') }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm leading-relaxed text-gray-700 dark:text-gray-300">

        <div class="bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4 shadow-sm">
            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">
                {{ __('dashboard.day_preferences_title') }}
            </h4>
            <p>{!! __('dashboard.day_preferences_description.auto') !!}</p>
            <br>
            <p>{!! __('dashboard.day_preferences_description.notify') !!}</p>
            <br>
            <p>{!! __('dashboard.day_preferences_description.none') !!}</p>
            <br>
            <p>{!! __('dashboard.day_preferences_description.after_join') !!}</p>
        </div>

        @if (! auth()->user()->notifications_disabled)
            <div class="bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-800 rounded-lg p-4 shadow-sm">
                <h4 class="font-semibold text-indigo-800 dark:text-indigo-300 mb-2">
                    {{ __('dashboard.any_day_preferences_title') }}
                </h4>
                <p>{!! __('dashboard.any_day_preferences_description.main') !!}</p>
                <br/>
                <p>{!! __('dashboard.any_day_preferences_description.delay') !!}</p>
                <br/>
                <p>{!! __('dashboard.any_day_preferences_description.backup') !!}</p>
                <br/>
                <p class="text-xs text-gray-600 dark:text-gray-400 italic mt-2">
                    {!! __('dashboard.any_day_preferences_description.tip', ['link' => route('notification-subscription.edit')]) !!}
                </p>
            </div>
        @endif
    </div>
</div>
