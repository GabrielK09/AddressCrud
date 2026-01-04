<?php

namespace App\Messages\Address\Request;

enum AddressDefaultMessages: string
{
    case CEP_REQUIRED = 'O CEP é obrigatório!';
    case CEP_STRING_FORMAT = 'O CEP precisa estar em um formato válido!';
    case CEP_MAX = 'O CEP precisa estar dentro do limite de caracteres (8)!';
    case CEP_MIN = 'O CEP precisa estar dentro do limite minímo de caracteres (8)!';
    case CEP_REGEX = 'O CEP precisa ser formatado para um formato válido!';
    case CEP_PROHIBITED = 'A alteração de um endereço não permite a alteração do CEP!';

    case STATE_REQUIRED = 'O Estado é obrigatório!';
    case STATE_STRING_FORMAT = 'O Estado precisa estar em um formato válido!';
    case STATE_MAX = 'O Estado precisa estar dentro do limite de caracteres (2)!';

    case CITY_REQUIRED = 'A cidade é obrigatória!';
    case CITY_STRING_FORMAT = 'A cidade precisa estar em um formato válido!';
    case CITY_MAX = 'A cidade precisa estar dentro do limite de caracteres (120)!';

    case NEIGHBORHOOD_REQUIRED = 'O bairro é obrigatória!';
    case NEIGHBORHOOD_STRING_FORMAT = 'O bairro precisa estar em um formato válido!';
    case NEIGHBORHOOD_MAX = 'O bairro precisa estar dentro do limite de caracteres (120)!';

    case STREET_REQUIRED = 'A rua é obrigatória!';
    case STREET_STRING_FORMAT = 'A rua precisa estar em um formato válido!';
    case STREET_MAX = 'A rua precisa estar dentro do limite de caracteres (200)!';
        
    case LONGITUDE_STRING_FORMAT = 'A longitude precisa estar em um formato válido!';
    case LONGITUDE_MAX = 'A longitude precisa estar dentro do limite de caracteres (200)!';

    case LATITUDE_STRING_FORMAT = 'A latitude precisa estar em um formato válido!';
    case LATITUDE_MAX = 'A latitude precisa estar dentro do limite de caracteres (200)!';
}
