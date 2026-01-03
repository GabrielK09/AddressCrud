<?php

namespace App\Http\Requests\Address;

use App\Messages\Address\Request\AddressDefaultMessages;
use App\Messages\DefaultMessages;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddressFullDataRequest extends FormRequest
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
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'cep' => ['required', 'string', 'max:8'],
            'state' => [$required, 'string', 'max:2'], 
            'city' => [$required, 'string', 'max:120'], 
            'neighborhood' => [$required, 'string', 'max:120'], 
            'street' => [$required, 'string', 'max:200'],  
            'longitude' => ['sometimes', 'string', 'max:200'], 
            'latitude' => ['sometimes', 'string', 'max:200'], 
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

            'state.required' => AddressDefaultMessages::STATE_REQUIRED->value, 
            'state.string' => AddressDefaultMessages::STATE_STRING_FORMAT->value, 
            'state.max' => AddressDefaultMessages::STATE_MAX->value, 

            'city.required' => AddressDefaultMessages::CITY_REQUIRED->value, 
            'city.string' => AddressDefaultMessages::CITY_STRING_FORMAT->value, 
            'city.max' => AddressDefaultMessages::CITY_MAX->value, 

            'neighborhood.required' => AddressDefaultMessages::NEIGHBORHOOD_REQUIRED->value, 
            'neighborhood.string' => AddressDefaultMessages::NEIGHBORHOOD_STRING_FORMAT->value, 
            'neighborhood.max' => AddressDefaultMessages::NEIGHBORHOOD_MAX->value, 

            'street.required' => AddressDefaultMessages::STREET_REQUIRED->value,  
            'street.string' => AddressDefaultMessages::STREET_STRING_FORMAT->value, 
            'street.max' => AddressDefaultMessages::STREET_MAX->value,  

            'longitude.string' => AddressDefaultMessages::LONGITUDE_STRING_FORMAT->value, 
            'longitude.max' => AddressDefaultMessages::LONGITUDE_MAX->value, 

            'latitude.string' => AddressDefaultMessages::LATITUDE_STRING_FORMAT->value, 
            'latitude.max' => AddressDefaultMessages::LATITUDE_MAX->value, 
            
        ];
    }
}
