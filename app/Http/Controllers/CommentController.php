<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\GameSession;
use App\Services\GroupNotificationService;
use App\Services\UserNotificationService;
use App\Services\XP;

class CommentController extends Controller
{
    public function store(CreateCommentRequest $request)
    {
        $gameSession = GameSession::where('uuid', $request->get('game_session_uuid'))->firstOrFail();

        $comment = Comment::updateOrCreate([
            'body' => $request->get('body'),
            'game_session_id' => $gameSession->id,
            'user_id' => auth()->user()->id,
        ]);

        XP::grantOncePerDay(auth()->user(), 'comment_session');

        if (auth()->user()->id !== $gameSession->organized_by) {
            app(UserNotificationService::class)->gameSessionOrganizerNewCommentAdded(
                $gameSession->id,
                $comment->id
            );
        }

        return redirect()->back()->withFragment('post-comment');
    }
}
