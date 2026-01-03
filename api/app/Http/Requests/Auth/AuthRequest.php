<?php

namespace App\Http\Requests\Auth;

use App\Messages\Auth\Request\AuthDefaultMessages;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:App\Models\User,email'],
            'password' => ['required', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => AuthDefaultMessages::NAME_REQUIRED->value,
            'name.string' => AuthDefaultMessages::NAME_STRING_FORMAT->value,
            'name.max' => AuthDefaultMessages::NAME_MAX->value,
            
            'email.required' => AuthDefaultMessages::EMAIL_REQUIRED->value,
            'email.email' => AuthDefaultMessages::EMAIL_STRING_FORMAT->value,
            'email.unique' => AuthDefaultMessages::EMAIL_UNIQUE->value,

            'password.required' => AuthDefaultMessages::PASSWORD_REQUIRED->value,
            'password.min' => AuthDefaultMessages::PASSWORD_MIN->value,                    
        ];
    }
}
