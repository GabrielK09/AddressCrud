<?php

namespace App\Http\Requests\Address;

use App\Messages\Address\Request\AddressDefaultMessages;
use Illuminate\Foundation\Http\FormRequest;

class AddressFullDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = $this->isMethod('POST') ? 'required' : 'sometimes';

        return [
            'cep' => $this->isMethod('POST') ? [
                'bail',    
                'required',    
                'string', 
                'min:9', 
                'max:9'
            ] : [
                ['prohibited']
            ],
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
            'cep.required' => AddressDefaultMessages::CEP_REQUIRED->value,
            'cep.prohibited' => AddressDefaultMessages::CEP_PROHIBITED->value,
            'cep.string' => AddressDefaultMessages::CEP_STRING_FORMAT->value,
            'cep.max' => AddressDefaultMessages::CEP_MAX->value,
            'cep.min' => AddressDefaultMessages::CEP_MIN->value,
            'cep.regex' => AddressDefaultMessages::CEP_REGEX->value,

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
