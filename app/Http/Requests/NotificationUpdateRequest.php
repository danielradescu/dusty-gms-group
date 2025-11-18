<?php

namespace App\Http\Requests;

use App\Enums\NotificationSubscriptionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NotificationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $allowedTypes = collect(NotificationSubscriptionType::cases())
            ->pluck('value')
            ->toArray();

        return [
            'subscriptions' => ['sometimes', 'array'],
            'subscriptions.*' => [
                'integer',
                Rule::in($allowedTypes), // ensure valid enum values only

                function ($attribute, $value, $fail) {
                    $user = auth()->user();

                    // Organizer-only subscriptions
                    if (in_array($value, array_map(fn($t) => $t->value, \App\Enums\NotificationSubscriptionType::organizerOptions()))) {
                        if (! $user->isOrganizer()) {
                            $fail('One of the selected subscriptions is invalid.');
                        }
                    }

                    // Admin-only subscriptions
                    if (in_array($value, array_map(fn($t) => $t->value, \App\Enums\NotificationSubscriptionType::adminOptions()))) {
                        if (! $user->hasAdminPermission()) {
                            $fail('One of the selected subscriptions is invalid.');
                        }
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'subscriptions.required' => 'Please select at least one subscription type.',
            'subscriptions.*.in' => 'One of the selected subscriptions is invalid.',
        ];
    }
}
