<?php

namespace App\Http\Requests\GameSession;

use App\Enums\GameSessionStatus;
use App\Models\GameSession;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrganizerRequest extends FormRequest
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

        // Deny if not found or status is not RECRUITING_PARTICIPANTS or CONFIRMED_BY_ORGANIZER
        return $this->gameSession && in_array($this->gameSession->status, [GameSessionStatus::RECRUITING_PARTICIPANTS, GameSessionStatus::CONFIRMED_BY_ORGANIZER]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'new_organizer_id' => [
                    'required',
                    'exists:users,id',
                    function ($attribute, $value, $fail) {
                        if (! $this->gameSession->registrations()->where('user_id', $value)->exists()) {
                            $fail('This user is not registered to this game session!');
                        };
                    }
                ]
        ];
    }

    public function messages(): array
    {
        return [
            'new_organizer_id.required' => 'Please a new organizer first.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = redirect()
            ->to(url()->previous() . '#change-organizer-management')
            ->withErrors($validator)
            ->withInput();

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
