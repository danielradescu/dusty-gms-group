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
        $comment = new Comment();

        $comment->body = $request->get('body');
        $comment->game_session_id = $gameSession->id;
        $comment->user_id = auth()->user()->id;
        $comment->save();

        XP::grantOncePerDay(auth()->user(), 'comment_session');
        if (auth()->user()->id !== $gameSession->organized_by) {
            app(UserNotificationService::class)->gameSessionOrganizerNewCommentAdded(
                $gameSession->id,
                $comment->id
            );
        }

        if ($request->has('is_announcement') && ($gameSession->organized_by == auth()->user()->id)) {
            //this is a general announcement comment from the organizer for participants
            $comment->is_announcement = true;
            $comment->save();

            app(GroupNotificationService::class)->gameSessionMessageFromOrganizer($gameSession->id, $comment->id);
        }

        return redirect()->back()->withFragment('post-comment');
    }
}
