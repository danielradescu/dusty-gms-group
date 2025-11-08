<section class="mt-10">
    <h3 class="text-lg font-semibold text-gray-100 mb-4 flex items-center gap-2">
        💬 Comments
    </h3>

    <!-- Comment form -->
    <form id="post-comment" method="POST" action="{{ route('comment.store') }}" class="mb-6">
        @csrf
        <textarea
            name="body"
            rows="3"
            placeholder="Share your thoughts about this session..."
            class="w-full p-3 rounded-lg border border-gray-700 bg-gray-900 text-gray-200
                   placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
        ></textarea>
        @error('body')
            <p class="text-sm text-red-400 mt-1">{{ $message }}</p>
        @enderror
        <input type="hidden" name="game_session_uuid" value="{{$gameSession->uuid}}">

        <div class="mt-2 flex justify-end">
            <button
                type="submit"
                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-md font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
            >
                Post Comment
            </button>
        </div>
    </form>
    <!-- Comment List -->
    <div class="space-y-5">
        @forelse($comments as $comment)
                <!-- Comment 1 -->
                <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset($comment->user->getPhotoURL()) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                            <span class="font-semibold text-gray-200">{{ $comment->user->name }}</span>
                        </div>
                        <span class="text-sm text-gray-500">{{ $comment->created_at->format('l, M j, Y · H:i') }}</span>
                    </div>
                    <p class="text-gray-300">{{ $comment->body }}</p>
                </div>
        @empty
            @php
                $messages = [
                    '💬 No comments yet — start the conversation!',
                    '🗨️ Be the first to comment and share your thoughts!',
                    '🎲 Got an opinion? Drop a comment!',
                ];
            @endphp
            <p class="italic text-gray-500 dark:text-gray-400">
                {{ $messages[array_rand($messages)] }}
            </p>
        @endforelse
    </div>
</section>
