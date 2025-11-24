<?php

namespace App\View\Components;

use App\Enums\JoinRequestStatus;
use Illuminate\View\Component;

class JoinRequestStatusBadge extends Component
{
    public string $status;

    public function __construct(JoinRequestStatus $joinRequestStatus = JoinRequestStatus::PENDING)
    {
        $this->status = $joinRequestStatus->name;
    }

    public function render()
    {
        return view('components.join-request-status-badge');
    }
}
