<?php

namespace App\Http\Requests\GameSession;

use App\Enums\GameSessionStatus;
use App\Models\GameSession;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
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

        // Deny if not found or status/type is not RECRUITING_PARTICIPANTS
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
            'status' => [
                'required',
                'string',
                Rule::in([
                    GameSessionStatus::CONFIRMED_BY_ORGANIZER->value,
                    GameSessionStatus::CANCELLED->value,
                ]),
                function ($attribute, $value, $fail) {
                    if ($this->gameSession->status == GameSessionStatus::CONFIRMED_BY_ORGANIZER) {
                        if ($value != GameSessionStatus::CANCELLED->value) {
                            $fail('Game session can ONLY be cancelled at this point in time.');
                        }
                    }
                }
            ],

            'cancel_reason' => [
                'nullable',
                'string',
                'max:1000',
                // required if status is canceled
                'required_if:status,' . GameSessionStatus::CANCELLED->value,
            ],

            'confirm_status' => [
                'accepted',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Please select a session status.',
            'status.in' => 'Invalid status option.',
            'cancel_reason.required_if' => 'Please provide a reason for canceling the session.',
            'confirm_status.accepted' => 'Please confirm that you are ready to update the session status.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = redirect()
            ->to(url()->previous() . '#status-session-management')
            ->withErrors($validator)
            ->withInput();

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }

}
