<?php

namespace App\Http\Controllers\JoinRequest;

use App\Enums\JoinRequestStatus;
use App\Http\Requests\MemberJoinRequest;
use App\Mail\CommunityJoinApprovedMail;
use App\Models\JoinRequest;

class MemberController extends \Illuminate\Routing\Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (! auth()->user()->canInvite()) {
                abort(403, 'You cannot invite other members yet.');
            }

            return $next($request);
        });
    }

    public function create()
    {
        $invites = JoinRequest::where('initiated_by', auth()->user()->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('join-request.member.create', compact('invites'));
    }

    public function store(MemberJoinRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // Check if there’s already a public join request (not initiated by a member)
        $communityJoinRequest = JoinRequest::whereNull('initiated_by')
            ->where('email', $validated['email'])
            ->first();

        if (! $communityJoinRequest) {
            $communityJoinRequest = JoinRequest::create(array_merge($validated, [
                'status' => JoinRequestStatus::PENDING->value,
                'initiated_by' => $user->id,
            ]));

        }

        if ($communityJoinRequest->status === JoinRequestStatus::PENDING) {
            //will allow direct validation by the invitation at the moment
            $communityJoinRequest->initiated_by = $user->id;
            $communityJoinRequest->reviewed_by = $user->id;
            $communityJoinRequest->reviewed_at = now();
            $communityJoinRequest->note = $communityJoinRequest->note . ' --> System: Member invited by #' . $user->id . ' ' . $user->name . ' to join the group.';
            $communityJoinRequest->status = JoinRequestStatus::APPROVED;
            $communityJoinRequest->save();
        }

        if ($communityJoinRequest->status == JoinRequestStatus::APPROVED) {
            \Mail::to($communityJoinRequest->email)->send(new CommunityJoinApprovedMail($communityJoinRequest));
        }

        return redirect()->back()->with('success', 'Your invite has been recorded successfully.');

    }
}

