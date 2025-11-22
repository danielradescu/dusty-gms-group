<?php

namespace App\View\Components;

use App\Enums\RegistrationStatus;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RegistrationStatusBadge extends Component
{
    public RegistrationStatus $status;

    public function __construct(RegistrationStatus $status)
    {
        $this->status = $status;
    }

    public function render(): View|Closure|string
    {
        return view('components.registration-status-badge');
    }
}
