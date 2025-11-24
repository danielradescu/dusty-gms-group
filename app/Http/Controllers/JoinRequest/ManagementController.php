<?php

namespace App\Http\Controllers\JoinRequest;

use App\Enums\JoinRequestStatus;
use App\Enums\Role;
use App\Http\Requests\UpdateJoinRequestRequest;
use App\Models\CommunityJoinRequest;
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
        $joinRequests = CommunityJoinRequest::orderByRaw(
            "CASE WHEN status = ? THEN 0 ELSE 1 END", [JoinRequestStatus::PENDING->value]
        )
            ->orderBy('created_at')
            ->paginate(10);

        return view('join-request.management.index')
            ->with(['joinRequests' => $joinRequests]);
    }

    public function edit(CommunityJoinRequest $joinRequest)
    {
        return view('join-request.management.edit')->with(['joinRequest' => $joinRequest]);
    }

    public function update(UpdateJoinRequestRequest $request, CommunityJoinRequest $joinRequest)
    {
        $joinRequest->status = $request->get('status');
        $joinRequest->save();

        return redirect()->back()->with(['status' => 'Request Status Updated']);
    }
}

