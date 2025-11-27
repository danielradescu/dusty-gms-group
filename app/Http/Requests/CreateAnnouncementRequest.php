<?php

namespace App\Http\Requests;

use App\Models\GameSession;
use Illuminate\Foundation\Http\FormRequest;

class CreateAnnouncementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        // If user is not logged in
        if (! $user) {
            return false;
        }

        // Fetch the session being referenced
        $gameSession = GameSession::where('uuid', $this->input('game_session_uuid'))->first();

        if (! $gameSession) {
            return false; // Let validation handle missing UUIDs
        }

        // Only allow if the user is an admin or the organizer of the session
        return $user->hasAdminPermission() || $gameSession->organized_by === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // The UUID of the game session must exist in the DB
            'game_session_uuid' => ['required', 'uuid', 'exists:game_sessions,uuid'],

            // The body of the comment must be between 5 and 1000 characters, no HTML allowed
            'announcement_body' => ['required', 'string', 'min:10', 'max:1000'],

        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = redirect()
            ->to(url()->previous() . '#post-announcement')
            ->withErrors($validator)
            ->withInput();

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
