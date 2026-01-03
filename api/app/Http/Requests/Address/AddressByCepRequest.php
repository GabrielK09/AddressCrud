<?php

namespace App\Http\Requests\Address;

use App\Messages\Address\Request\AddressDefaultMessages;
use App\Messages\DefaultMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddressByCepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'cep' => formatCEP($this->cep)
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'cep' => ['required', 'string', 'max:8'],  
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => DefaultMessages::USER_ID->value,
            'user_id.exists' => DefaultMessages::USER_EXISTS->value,

            'cep.required' => AddressDefaultMessages::CEP_REQUIRED->value,
            'cep.string' => AddressDefaultMessages::CEP_STRING_FORMAT->value,
            'cep.max' => AddressDefaultMessages::CEP_MAX->value,
        ];
    }
}
