<div
    class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-5 shadow-inner border border-gray-200 dark:border-gray-700">
    <h3 class="text-sm uppercase tracking-wide text-gray-500 dark:text-gray-400 font-semibold mb-3">
        Notify Participants
    </h3>

    <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
        Once a session is confirmed, its details can no longer be edited. However, if you need to share an important announcement or a last-minute update, you can use this section to instantly notify all participants.
    </p>
{{--            @include('partials._errors')--}}
    <div class=" overflow-hidden shadow-sm sm:rounded-lg">
<!-- Organizer Announcement Form -->
<form id="post-announcement" method="POST" action="{{ route('announcement.store') }}" x-data="{ confirmed: false }" class="mb-8">
    @csrf

    <!-- Hidden fields -->
    <input type="hidden" name="game_session_uuid" value="{{ $gameSession->uuid }}">

    <!-- Announcement Textarea -->
    <textarea
        name="announcement_body"
        rows="4"
        placeholder="Write your announcement to all participants..."
        class="w-full p-3 rounded-lg border border-gray-700 bg-gray-900 text-gray-200
               placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500
               focus:border-transparent resize-none"
    ></textarea>
    @error('announcement_body')
    <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
    @enderror

    <!-- Confirmation Checkbox -->
    <div class="mt-4">
        <label
            class="flex items-start gap-3 p-4 rounded-lg border border-gray-700 bg-gray-800/50 hover:bg-gray-800
                   transition cursor-pointer"
        >
            <input
                type="checkbox"
                x-model="confirmed"
                class="mt-1 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-600 rounded"
            >
            <span class="text-sm text-gray-300 leading-relaxed">
                <strong>Yes, send this as an announcement to <span class="font-semibold text-indigo-600 dark:text-indigo-400">{{ $confirmedNotifyCount }}</span> confirmed participant{{ $confirmedNotifyCount !== 1 ? 's' : '' }}.</strong><br>
                This message will be sent via <span class="text-indigo-400 font-semibold">email</span> and <span class="text-indigo-400 font-semibold">other means of notifications</span>.
                Please confirm you want to notify everyone.
            </span>
        </label>
    </div>

    <!-- Submit Button (shown only when confirmed) -->
    <div class="mt-4 flex justify-end" x-show="confirmed" x-transition>
        <button
            type="submit"
            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-md font-medium shadow-sm
                   focus:outline-none focus:ring-2 focus:ring-indigo-400"
        >
            🚀 Send Announcement
        </button>
    </div>
</form>
    </div></div>
