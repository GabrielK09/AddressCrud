<?php

namespace App\Messages;

enum DefaultMessages: string
{
    case EMAIL_PROHIBITED = 'O e-mail do usuário não pode ser alterada por esse meio!';
    case PASSWORD_PROHIBITED = 'A senha do usuário não pode ser alterada por esse meio!';
}
