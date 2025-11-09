<?php

namespace App\Http\Requests;

use App\Services\GameSessionSlotService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GameSessionRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        // assuming any logged in user can make session requests
        return auth()->check();
    }

    public function rules(): array
    {
        $allowedDatetimes = collect(GameSessionSlotService::getAvailableSlots(collect()))
            ->pluck('dt')
            ->map(fn($dt) => $dt->format('Y-m-d H:i:s'))
            ->toArray();

        return [
            'requests' => ['required', 'array'],
            // For each entry inside "requests"
            'requests.*' => [
                'nullable',
                Rule::in(['auto', 'notify', null, '']),
                function ($attribute, $value, $fail) use ($allowedDatetimes) {
                    // Extract the datetime key from "requests.2025-11-15 15:00:00"
                    $parts = explode('.', $attribute);
                    $dateTime = $parts[1] ?? null;

                    if (!in_array($dateTime, $allowedDatetimes)) {
                        $fail('Invalid time slot selected: ' . $dateTime);
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'requests.required' => 'Please select at least one day to request.',
            'requests.*.in' => 'Invalid option selected — please use the provided form buttons.',
        ];
    }
}
