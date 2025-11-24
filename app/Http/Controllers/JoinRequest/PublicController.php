<?php

namespace App\Http\Controllers\JoinRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicJoinRequest;
use App\Models\CommunityJoinRequest;

class PublicController extends Controller
{
    public function create()
    {
        return view('join-request.public.create');
    }

    public function store(PublicJoinRequest $request)
    {
        $validatedData = $request->validated();

        CommunityJoinRequest::create($validatedData);

        return redirect()
            ->back()
            ->with('success', 'Your request has been submitted successfully! We will contact you soon.');
    }
}

