<?php

namespace App\Http\Requests;

use App\Enums\JoinRequestStatus;
use App\Models\JoinRequest;
use Illuminate\Foundation\Http\FormRequest;

class PublicJoinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', function ($attribute, $value, $fail) {
                $existing = JoinRequest::where('email', $value)
                    ->where('status', JoinRequestStatus::PENDING->value)
                    ->first();

                if ($existing) {
                    $fail('We already received a request to join for this email address.');
                }
            }],
            'phone' => ['nullable', 'string', 'max:50'],
            'other_means_of_contact' => ['nullable', 'string', 'max:1000'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
