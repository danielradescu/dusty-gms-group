<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAnnouncementRequest;
use App\Models\Comment;
use App\Models\GameSession;
use App\Services\GroupNotificationService;
use App\Services\UserNotificationService;
use App\Services\XP;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function store(CreateAnnouncementRequest $request)
    {
        $gameSession = GameSession::where('uuid', $request->get('game_session_uuid'))->firstOrFail();
        $comment = new Comment();

        $comment->body = $request->get('announcement_body');
        $comment->game_session_id = $gameSession->id;
        $comment->user_id = auth()->user()->id;
        $comment->is_announcement = true;
        $comment->save();

        app(GroupNotificationService::class)->gameSessionMessageFromOrganizer($gameSession->id, $comment->id);

        return redirect()->back()->withFragment('post-announcement');
    }
}
