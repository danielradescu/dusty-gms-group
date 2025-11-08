<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\GameSession;

class CommentController extends Controller
{
    public function store(CreateCommentRequest $request)
    {
        $gameSession = GameSession::where('uuid', $request->get('game_session_uuid'))->firstOrFail();
        $comment = new Comment();

        $comment->body = $request->get('body');
        $comment->game_session_id = $gameSession->id;
        $comment->user_id = $request->user()->id;
        $comment->save();

        return redirect()->back()->withFragment('post-comment');
    }
}
