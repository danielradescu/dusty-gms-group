<?php

namespace App\Http\Requests\GameSession;

use App\Enums\GameComplexity;
use App\Enums\GameSessionStatus;
use App\Models\GameSession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCoreInfoRequest extends FormRequest
{

    private $gameSession = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // ✅ Fetch GameSession by UUID
        $this->gameSession = GameSession::where('uuid', $this->route('uuid'))->firstOrFail();

        /**
         * cannot alter session once it started
         */
        if ($this->gameSession->start_at < now()) {
            return false;
        }

        // Core info data can be updated while recruiting participants
        return $this->gameSession && in_array($this->gameSession->status, [GameSessionStatus::RECRUITING_PARTICIPANTS]);
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

            'start_at_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    // The date comes from the existing session
                    $uuid = request()->route('uuid');
                    $session = \App\Models\GameSession::where('uuid', $uuid)->first();

                    if (! $session) {
                        return $fail('Invalid game session.');
                    }

                    // Combine stored date with new time
                    $start = $session->start_at->copy()->setTimeFromTimeString($value);

                    // Validate time offset (at least 2 hours from now)
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

            'confirm_core_info' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'confirm_core_info.accepted' => 'Please confirm that you have reviewed all details before saving changes.',
        ];
    }
}
