<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberJoinRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'phone', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide the name of the person you want to invite.',
            'email.required' => 'Please provide the email address of the invitee.',
            'email.email' => 'Please provide a valid email address.',
        ];
    }

}
