<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\JoinRequestStatus;

class UpdateJoinRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasOrganizerPermission();
    }

    public function rules(): array
    {
        $joinRequest = $this->route('joinRequest'); // assuming route model binding

        // Prevent changing status if already approved
        if ($joinRequest && $joinRequest->status === JoinRequestStatus::APPROVED) {
            abort(403, 'Approved requests cannot be modified.');
        }

        // Dynamically allow all enum values except 'approved'
        $validStatuses = collect(JoinRequestStatus::cases())
            ->map(fn($case) => $case->value)
            ->implode(',');

        return [
            'status' => [
                'required',
                'integer',
                'in:' . $validStatuses,
            ],
            'note' => ['required', 'string', 'min:5', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'A status selection is required.',
            'status.in' => 'Invalid status selected. Approved requests cannot be changed.',
            'note.required' => 'Please provide a note describing your contact with the applicant.',
        ];
    }
}

