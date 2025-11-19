<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\GameSession;
use App\Services\UserNotificationService;
use App\Services\XP;

class CommentController extends Controller
{
    public function store(CreateCommentRequest $request)
    {
        $gameSession = GameSession::where('uuid', $request->get('game_session_uuid'))->firstOrFail();
        $comment = new Comment();

        $comment->body = $request->get('body');
        $comment->game_session_id = $gameSession->id;
        $comment->user_id = auth()->user()->id;
        $comment->save();

        XP::grantOncePerDay(auth()->user(), 'comment_session');
        app(UserNotificationService::class)->gameSessionOrganizerNewCommentAdded(
            $gameSession->id,
            $comment->id
        );

        return redirect()->back()->withFragment('post-comment');
    }
}
