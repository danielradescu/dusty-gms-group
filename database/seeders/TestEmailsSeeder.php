<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;
use App\Mail\{GameSessionAutoJoinedMail,
    GameSessionCreatedMail,
    GameSessionCanceledMail,
    GameSessionConfirmedMail,
    GameSessionOpenSlotAvailableMail,
    GameSessionOrganizerCommentAddedMail,
    GameSessionOrganizerNewCommentMail,
    GameSessionOrganizerUpdateMail,
    GameSessionReminderMail,
    OrganizerOfASessionMail,
    OrganizerPromptCreateGameSessionMail};
use App\Mail\CommunityJoinApprovedMail;
use App\Models\{
    User,
    GameSession,
    JoinRequest
};
use Carbon\Carbon;

class TestEmailsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📨 Sending all test emails...');

        // Fake user
        $user = User::first();

        // Fake game session
        $session = GameSession::first();


        // Fake join request
        $joinRequest = JoinRequest::first();


        Mail::to($joinRequest->email)->send(new CommunityJoinApprovedMail($joinRequest)); //1
        Mail::to($user->email)->send(new GameSessionAutoJoinedMail($user, $session, date('Y-m-d H:i:s'))); //2
        Mail::to($user->email)->send(new GameSessionCanceledMail($user, $session));//3
        Mail::to($user->email)->send(new GameSessionConfirmedMail($user, $session));//4
        Mail::to($user->email)->send(new GameSessionCreatedMail($user, $session));//5
        Mail::to($user->email)->send(new GameSessionOpenSlotAvailableMail($user, $session));//5
        Mail::to($user->email)->send(new GameSessionOrganizerNewCommentMail($user, $session));//7
        Mail::to($user->email)->send(new GameSessionOrganizerUpdateMail($user, $session, 'TEST MESSAGE'));//8
        Mail::to($user->email)->send(new GameSessionReminderMail($user, $session));//9
        Mail::to($user->email)->send(new OrganizerOfASessionMail($user, $session));//10
        Mail::to($user->email)->send(new OrganizerPromptCreateGameSessionMail($user, date('Y-m-d H:i:s'), rand(2,10)));//11

        $this->command->info('✅ All test emails have been sent to ' . $user->email . ' and ' . $joinRequest->email);
    }
}
