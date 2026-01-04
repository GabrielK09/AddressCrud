<?php

namespace App\Http\Requests\Address;

use App\Messages\Address\Request\AddressDefaultMessages;
use Illuminate\Foundation\Http\FormRequest;

class AddressByCepRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'cep' => $this->cep ? formatCEP($this->cep) : null
        ]);
    }

    public function rules(): array
    {
        return [
            'cep' => ['required', 'string', 'max:8'],  
        ];
    }

    public function messages()
    {
        return [
            'cep.required' => AddressDefaultMessages::CEP_REQUIRED->value,
            'cep.string' => AddressDefaultMessages::CEP_STRING_FORMAT->value,
            'cep.max' => AddressDefaultMessages::CEP_MAX->value,
        ];
    }
}
