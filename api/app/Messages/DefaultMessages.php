<?php

namespace App\Messages;

enum DefaultMessages: string
{
    case USER_ID = 'O identificador do usuário precisa estar em um formato válido!';
    case USER_EXISTS = 'Usuário não localizado!';
}
