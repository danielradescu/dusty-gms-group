<div
    class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 shadow-inner border border-gray-200 dark:border-gray-700">

    <div class="mb-4">
        <x-link-button class="!w-auto" href="{{ route('game-session.interaction.show', $gameSession->uuid) }}" variant="secondary">← Back to session view</x-link-button>
    </div>

    <h3 class="text-sm uppercase tracking-wide text-gray-500 dark:text-gray-400 font-semibold mb-3">
        Notify Participants
    </h3>

    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
        Once a session is confirmed, its details can no longer be edited. However, if you need to share an important
        announcement or a last-minute update, you can use this section to instantly notify all participants.
    </p>
    {{--            @include('partials._errors')--}}
    <div class=" overflow-hidden shadow-sm sm:rounded-lg">
        <!-- Organizer Announcement Form -->
        <form id="post-announcement" method="POST" action="{{ route('announcement.store') }}"
              x-data="{ confirmed: false }" class="mb-8">
            @csrf

            <!-- Hidden fields -->
            <input type="hidden" name="game_session_uuid" value="{{ $gameSession->uuid }}">

            <!-- Announcement Textarea -->
            <textarea
                name="announcement_body"
                rows="4"
                placeholder="Write your announcement to all participants..."
                class="w-full p-3 rounded-lg border resize-none
               border-gray-300 bg-white text-gray-800 placeholder-gray-500
               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
               dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:placeholder-gray-500"
            ></textarea>
            @error('announcement_body')
            <p class="text-sm text-red-500 dark:text-red-400 mt-1">{{ $message }}</p>
            @enderror

            <!-- Confirmation Checkbox -->
            <div class="mt-4">
                <label
                    class="flex items-start gap-3 p-4 rounded-lg border transition cursor-pointer
                   border-gray-200 bg-gray-50 hover:bg-gray-100
                   dark:border-gray-700 dark:bg-gray-800/50 dark:hover:bg-gray-800"
                >
                    <input
                        type="checkbox"
                        x-model="confirmed"
                        class="mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded
                       dark:border-gray-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-gray-900"
                    >
                    <span class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                <strong>
                    Yes, send this as an announcement to
                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">
                        {{ $confirmedNotifyCount }}
                    </span>
                    confirmed participant{{ $confirmedNotifyCount !== 1 ? 's' : '' }}.
                </strong><br>
                This message will be sent via
                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">email</span>
                and
                <span class="text-indigo-600 dark:text-indigo-400 font-semibold">other means of notifications</span>.
                Please confirm you want to notify everyone.
            </span>
                </label>
            </div>

            <!-- Submit Button (shown only when confirmed) -->
            <div class="mt-4 flex justify-end" x-show="confirmed" x-transition>
                <x-button class="!w-auto" variant="primary">🚀 Send Announcement</x-button>
            </div>
        </form>

    </div>
</div>
