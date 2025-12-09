<?php

namespace App\Http\Controllers\JoinRequest;

use App\Enums\JoinRequestStatus;
use App\Enums\Role;
use App\Http\Requests\UpdateJoinRequestRequest;
use App\Mail\CommunityJoinApprovedMail;
use App\Models\JoinRequest;
use Illuminate\Routing\Controller;

class ManagementController extends Controller
{

    public function __construct()
    {
        // Only allow admins and organizers
        $this->middleware(['auth', 'hasPermission:' . Role::Organizer->name]);
    }

    public function index()
    {
        $joinRequests = JoinRequest::orderByRaw(
            "CASE WHEN status = ? THEN 0 ELSE 1 END", [JoinRequestStatus::PENDING->value]
        )
            ->orderBy('created_at')
            ->paginate(10);

        return view('join-request.management.index')
            ->with(['joinRequests' => $joinRequests]);
    }

    public function edit(JoinRequest $joinRequest)
    {
        if (! $joinRequest->isEditable) {
            abort(403, 'Cannot change status for this join request / invitation.');
        }
        return view('join-request.management.edit')->with(['joinRequest' => $joinRequest]);
    }

    public function update(UpdateJoinRequestRequest $request, JoinRequest $joinRequest)
    {
        if (! $joinRequest->isEditable) {
            abort(403, 'Cannot change status for this join request / invitation.');
        }

        $joinRequest->status = $request->get('status');
        $joinRequest->reviewed_by = auth()->user()->id;
        $joinRequest->save();

        if ($joinRequest->status == JoinRequestStatus::APPROVED) {
            \Mail::to($joinRequest->email)->send(new CommunityJoinApprovedMail($joinRequest));
        }

        return redirect()->back()->with(['status' => 'Request Status Updated']);
    }
}

