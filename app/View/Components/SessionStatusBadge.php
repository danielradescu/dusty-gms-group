<?php

namespace App\View\Components;

use App\Enums\GameSessionStatus;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SessionStatusBadge extends Component
{
    public GameSessionStatus $status;

    public function __construct(GameSessionStatus $status)
    {
        $this->status = $status;
    }

    public function render(): View|Closure|string
    {
        return view('components.session-status-badge');
    }
}
