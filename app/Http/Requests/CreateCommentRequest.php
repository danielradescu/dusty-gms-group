<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
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
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = redirect()
            ->to(url()->previous() . '#post-comment')
            ->withErrors($validator)
            ->withInput();

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
