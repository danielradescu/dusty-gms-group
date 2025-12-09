<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Plan This Week’s Play Days
        </h2>
    </x-slot>

    <div class="py-12 pb-0">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 mt-1">

            <!-- ABOUT SECTION -->
            <div class="mb-6 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">
                    🗓️ About Play Planning
                </h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    Define which <strong>days of this week</strong> are playable.
                    <strong>Saturday</strong> and <strong>Sunday</strong> are always active and cannot be changed.
                    The <strong>current day</strong> is shown in red and cannot be modified.
                </p>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mt-3">
                    Every day resets to non-playable after one week,
                    except <strong>Saturday</strong> and <strong>Sunday</strong>, which remain permanently playable.
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 italic">
                    💡 Click on any circle to toggle playable (green) or non-playable (red).
                    The current day is shown in red and disabled.
                </p>
            </div>

            <!-- MAIN FORM -->
            <form action="{{ route('days-we-play.update') }}" method="POST" class="space-y-8"
                  x-data="dayPlanner(@js($selectedDays ?? []))">
                @csrf
                @method('PATCH')

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <h3 class="font-semibold text-lg mb-4 text-center">🎯 Select Playable Days</h3>

                        <!-- RADIAL LAYOUT -->
                        <div class="flex justify-center mt-8 relative">
                            <div class="relative w-64 h-64 rounded-full">

                                <template x-for="(day, index) in days" :key="day.short">
                                    <button
                                        type="button"
                                        @click="toggle(day)"
                                        class="absolute w-10 h-10 flex items-center justify-center rounded-full text-xs font-bold
                                               transition-all duration-300 shadow-md hover:scale-105"
                                        :style="`
                                            left: calc(${day.x}% - 20px);
                                            top: calc(${day.y}% - 20px);
                                            background-color: ${day.locked
                                                ? (day.short === currentShort ? '#ef4444' : '#22c55e')
                                                : (day.playable ? '#22c55e' : '#ef4444')};
                                            opacity: ${day.locked ? 1 : 0.9};
                                            cursor: ${day.locked ? 'default' : 'pointer'};
                                        `"
                                        x-text="day.short.toUpperCase()"
                                        :disabled="day.locked"
                                    ></button>
                                </template>

                                <!-- Hidden Inputs -->
                                <template x-for="day in days" :key="day.short">
                                    <input type="hidden"
                                           :name="`days[${day.short}]`"
                                           :value="day.playable || (day.locked && day.short !== currentShort) ? 1 : 0">
                                </template>
                            </div>
                        </div>

                        <div class="text-center mt-6">
                            <x-button variant="primary" class="px-6 py-2">
                                💾 Save Playable Days
                            </x-button>
                        </div>

                        @if (session('status'))
                            <div class="mt-4 text-sm text-green-600 dark:text-green-300 text-center">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            </form>

            <!-- DAY LIST BELOW -->
            <div class="mt-10 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-3">
                    📋 Current Week Overview
                </h3>

                <table class="w-full text-sm text-left text-gray-600 dark:text-gray-300 border-collapse">
                    <thead>
                    <tr class="text-gray-700 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                        <th class="py-2 px-3">Day</th>
                        <th class="py-2 px-3 text-center">Playable</th>
                        <th class="py-2 px-3 text-center">Changed By</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($daysList as $day)
                        @php
                            $isToday = $daysWithDates[$day->day_of_week]['date'] === 'today';
                        @endphp

                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-2 px-3 font-medium">
                                {{ $daysWithDates[$day->day_of_week]['label'] }}
                                <span class="text-xs text-gray-500 ml-1">
                                    ({{ $isToday ? now()->format('d/m') : $daysWithDates[$day->day_of_week]['date'] }})
                                </span>

                                @if($isToday)
                                    <span class="bg-indigo-500 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold ml-2 animate-pulse">
                                        TODAY
                                    </span>
                                @endif
                            </td>

                            <td class="py-2 px-3 text-center">
                                @if($day->playable)
                                    <span class="px-2 py-1 rounded-full bg-green-600 text-white text-xs">YES</span>
                                @else
                                    <span class="px-2 py-1 rounded-full bg-red-600 text-white text-xs">NO</span>
                                @endif
                            </td>

                            <td class="py-2 px-3 text-center text-xs text-gray-500 dark:text-gray-400">
                                {{ $day->changed_by ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>


                </table>
            </div>

        </div>
    </div>

    <script>
        function dayPlanner(preselected = []) {
            const allDays = ['su', 'mo', 'tu', 'we', 'th', 'fr', 'sa'];
            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const todayIndex = new Date().getDay();
            const currentShort = allDays[todayIndex];

            // Include all 7 days, but mark su/sa and today as locked
            const total = 7;
            const step = (2 * Math.PI) / total;
            const center = 50;
            const radius = 40;

            const days = allDays.map((short, i) => {
                const angle = i * step - Math.PI / 2;
                const x = center + radius * Math.cos(angle);
                const y = center + radius * Math.sin(angle);
                const locked = ['sa', 'su'].includes(short) || short === currentShort;

                return {
                    short,
                    name: dayNames[i],
                    locked,
                    playable: (['sa', 'su'].includes(short)) || (preselected.includes(short) && !locked),
                    x,
                    y
                };
            });

            return {
                days,
                currentShort,
                toggle(day) {
                    if (day.locked) return;
                    day.playable = !day.playable;
                }
            };
        }
    </script>
</x-app-layout>
