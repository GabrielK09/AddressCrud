<?php

namespace App\Messages\Auth\Request;

enum AuthDefaultMessages: string
{
    // name
    // email
    // password
    case NAME_REQUIRED = 'O nome do usuário é obrigatório!';
    case NAME_STRING_FORMAT = 'O nome do usuário precisa estar em um formato válido!';
    case NAME_MAX = 'O nome do usuário precisa estar dentro do limite de caracteres (120)!';

    case EMAIL_REQUIRED = 'O e-mail do usuário é obrigatório!';
    case EMAIL_STRING_FORMAT = 'O e-mail do usuário precisa estar em um formato válido!';
    case EMAIL_UNIQUE = 'Esse e-mailo de usuário já está cadastrado!';

    case PASSWORD_REQUIRED = 'A senha do usuário é obrigatório!';
    case PASSWORD_MIN = 'A senha do usuário precisa ter pelo menos 8 caracteres!';
}
