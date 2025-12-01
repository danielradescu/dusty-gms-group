<?php

namespace App\Http\Controllers\GameSession;

use App\Enums\GameSessionStatus;
use App\Enums\RegistrationStatus;
use App\Http\Requests\GameSession\FinalizeSessionRequest;
use App\Models\GameSession;
use App\Services\XP;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FinalizeGameSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdminOrGameSessionOwner']);
    }

    public function create($uuid)
    {
        $gameSession = GameSession::with('registrations', 'registrations.user')->where('uuid', $uuid)->firstOrFail();
        if (! $gameSession->canChangeStatus()) {
            abort(403, "This session is no longer editable.");
        }

        $toReturn = [
            'gameSession' => $gameSession,
            'confirmedRegistrations' => $gameSession->registrations()->where('status', RegistrationStatus::Confirmed)->get(),
        ];


        return view('game-session.finalize.create')->with($toReturn);
    }

    public function store(Request $request)
    {

        $session = GameSession::with('registrations', 'registrations.user', 'registrations.user.inviter', 'organizer')
            ->where('uuid', $request->uuid)
            ->firstOrFail();

        if (! $session->canChangeStatus()) {
            abort(403, "This session is no longer editable.");
        }

        // Ensure session is in the past
        if ($session->start_at->isFuture()) {
            throw ValidationException::withMessages([
                'session' => 'You can only finalize sessions that have already occurred.'
            ]);
        }

        // Build dynamic validation rules
        $confirmedRegistrations = $session->registrations()
            ->where('status', \App\Enums\RegistrationStatus::Confirmed->value)
            ->pluck('id')
            ->toArray();

        $rules = [
            'confirm_finalize' => ['required', 'accepted'], // must be true
            'result' => ['required', Rule::in(['success', 'fail'])],
            'note' => ['nullable', 'string'],
        ];

        // Add dynamic attendance rules
        foreach ($confirmedRegistrations as $registrationId) {
            $rules["attendance.$registrationId"] = ['nullable', Rule::in(['participated', 'absent'])];
        }

        $validated = $request->validate($rules);




        $isSessionSuccess = $validated['result'] == 'success';

        if ($isSessionSuccess) {

            // Check manually that all participants have attendance
            $missing = collect($confirmedRegistrations)
                ->filter(fn($id) => empty($validated['attendance'][$id] ?? null))
                ->isNotEmpty();

            if ($missing) {
                throw ValidationException::withMessages([
                    'attendance' => 'Need to set attendance for each participant.',
                ]);
            }

            try {
                DB::transaction(function () use ($session, $validated) {
                    // Save data
                    foreach ($validated['attendance'] as $registrationId => $status) {
                        $registration = $session->registrations()->find($registrationId);
                        if ($registration) {
                            $registration->update(['participated' => true]);
                            if ($user = $registration->user) {
                                //will reward the participant
                                XP::grant($user, 'participate_completed_successful_session');
                                if ($user->invited_by) {
                                    //will reward the person that invited this good participant
                                    XP::grant($user->inviter, 'invited_new_member_plays_a_session');
                                }
                            }
                        }
                    }

                    $session->update([
                        'status' => GameSessionStatus::SUCCEEDED,
                    ]);
                    //the organizer is rewarded for a successful session
                    XP::grant($session->organizer, 'organizer_completed_successful_session');

                });
            } catch (\Throwable $e) {
                Log::error('Finalizing a session failed', [
                    'error' => $e->getMessage(),
                    'session_id' => $session->id,
                    'session_uuid' => $session->uuid,
                ]);

                return redirect()->back()->with('error', 'Error finalizing the session, please contact the administrator.');
            }


        } else {
            $session->update([
                'status' => GameSessionStatus::FAILED,
            ]);
        }

        $note = trim($validated['note'] ?? '');

        $session->update([
            'note' => $note !== ''
                ? $note
                : '🎲 Thank you all for participating in this session! Hope to see you again soon for more great games.',
        ]);

        return redirect()
            ->route('game-session.interaction.show', $session->uuid)
            ->with('status', 'Session finalized successfully!');

    }
}
