<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📅 Manage Extended Weekends
        </h2>
    </x-slot>


    <div class="py-12 pb-0">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 mt-1">

            <div class="mb-6 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase mb-2">
                    🧭 About Extended Weekends
                </h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                    By default, every game-planning week uses <strong>Saturday–Sunday</strong> as the standard weekend.
                    Sometimes, however, the weekend might be extended — for example, when players are available earlier
                    (Thursday or Friday) or want to continue into Monday or Tuesday.
                </p>
                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed mt-3">
                    Use this page to define an <strong>extended weekend</strong> for the current week.
                    Only exceptions are stored — if you reset to <strong>Saturday–Sunday</strong>, the system
                    automatically removes the custom range.
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 italic">
                    💡 The selected weekend range affects all availability requests, session creation prompts, and
                    auto-join logic for that week. Choose carefully and make changes between weekends (extended or not).
                </p>
            </div>

            <form action="{{ route('extended-weekend.update') }}" method="POST" class="space-y-8">
                @csrf
                @method('PATCH')

                <!-- CURRENT WEEKEND SECTION -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="p-6 text-gray-900 dark:text-gray-100">

                        <h3 class="font-semibold text-lg mb-2">🎯 Extend Current Weekend</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Default is <strong>Saturday–Sunday</strong>. Override only if this weekend is extended.
                        </p>

                        @if($author)
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                Defined by <strong>{{ $author }}</strong>
                            </p>
                        @else
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                                Currently using <strong>default Saturday–Sunday</strong>.
                            </p>
                        @endif

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-input-label for="start" value="Start Day"/>
                                <select id="start" name="start" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @foreach($startOptions as $key => $label)
                                        <option value="{{ $key }}" @selected(old('start', $start) === $key)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('start')" class="mt-2"/>
                            </div>

                            <div>
                                <x-input-label for="end" value="End Day"/>
                                <select id="end" name="end" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @foreach($endOptions as $key => $label)
                                        <option value="{{ $key }}" @selected(old('end', $end) === $key)>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('end')" class="mt-2"/>
                            </div>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="comment" value="Comment (optional)"/>
                            <textarea id="comment" name="comment" rows="3"
                                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('comment', $comment) }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2"/>
                        </div>
                        <div class="pt-4">
                            <x-button variant="primary">💾 Save Extended Weekend</x-button>
                        </div>
                        @if (session('status'))
                            <div class="mt-4 text-sm text-green-600 dark:text-green-300">
                                {{ session('status') }}
                            </div>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
