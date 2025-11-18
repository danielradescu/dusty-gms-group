<?php

namespace App\Http\Requests;

use App\Enums\GameComplexity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGameSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasOrganizerPermission();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'start_at' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $start = \Carbon\Carbon::parse($value);
                    if ($start->lessThan(now()->addHours(2))) {
                        $fail('The game session must start at least 2 hours from now.');
                    }
                },
            ],

            'min_players' => [
                'required',
                'integer',
                'min:1',
                'lt:max_players', // less than max
            ],

            'max_players' => [
                'required',
                'integer',
                'gt:min_players', // greater than min
                'max:12',
            ],

            'complexity' => [
                'nullable',
                'integer',
                Rule::in(array_column(GameComplexity::cases(), 'value')),
            ],

            'delay_until' => [
                'nullable',
                'boolean',
            ],

            // Organizer select — optional only for admins
            'organized_by' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if (! empty($value)) {
                        if (! auth()->user()->hasAdminPermission()) {
                            $fail('You are not authorized to assign an organizer.');
                        }
                    }
                }
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter a name for the session.',
            'location.required' => 'Specify where the session will take place.',
            'start_at.required' => 'Select a date and time for the session.',
            'start_at.after' => 'The session start time must be in the future.',
            'min_players.required' => 'Enter the minimum number of players.',
            'min_players.lt' => 'Minimum players must be less than the maximum.',
            'max_players.required' => 'Enter the maximum number of players.',
            'max_players.gt' => 'Maximum players must be greater than the minimum.',
            'complexity.between' => 'Complexity should be between 0 and 5.',
            'organized_by.required' => 'You must assign an organizer (admins only).',
            'organized_by.exists' => 'The selected organizer is invalid.',
        ];
    }
}
