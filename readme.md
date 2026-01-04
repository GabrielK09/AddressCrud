# 📌 Geral

### 🛠 Ferramentas

- Laravel 12+
- PHP 8+
- Composer 2.7

## 🏛️ Arquitetura 

A arquitetura utilizada segue as camadas do **Repository Pattern**, visando uma divisão de responsabilidades, fácil implementação e manutenções do código e também seguindo o modelo do MVC do Laravel:
<br>

<!--img src="https://arquivo.devmedia.com.br/REVISTAS/easyjava/imagens/9/3/image001.jpg"/-->
### 📁 Dividindo por camadas

```tree
Controller
    ↓
Services/DTO 
    ↓
Repository/Interfaces
    ↓
Model/DB
```

### Estrutura
Para o uso da nossa API, foi preciso fazer uma pequena configuração da mesma, começando pela criação de um arquivo no diretório 

```tree
api
└─── config
     └─── address.php

```

E inserir o código:

```php
<?php

return [
    'api_url' => env('ADDRESS_API_URL_V2')
];
```

Como a API vai ser utilizada na nossa camada do \Service, vamos 'ligar' essas duas partes:

```tree

api
└─── app
     └─── Provides
     	  └─── AppServiceProvider.php

```

E inserir o código:

```php
$this->app>singleton(AddressService::class, function() {
    return new AddressService(
        config('address.api_url'),
        $this->app->make(AddressContract::class)
        
    );
});
```

O trecho `$this->app->singleton` irá fazer essa ligação da configuração, passando primeiramente quem vai receber os parâmetros, o `AddressService`, com uma nova instância da classe com a configuração da API, e o segundo parâmetro é referente a ligação da `Interface` com o `Repository` na camada do `Eloquent`, mais para frente será explicado.

E por esse meio, conseguimos pegar o valor da tag da API de consulta.

Em nosso Service fazemos um `construct` para iniciar essas dependencias e tornar elas acessíveis no código:

```tree
api
└─── app
     └─── Services
     	  └─── Address
     	  	   └─── AddressService.php

```

```php
public function __construct(
    private string $apiUrl,
    protected AddressContract $addressRepository
){}
```
## 🔗 Rotas da API

## 🏠 Endereços - AddressController

| Método | Rota                                 | Descrição                                  | Auth |
|-------:|--------------------------------------|--------------------------------------------|:----:|
| <span style="color: rgb(107, 221, 154)">GET</span>    | /api/v1/address/index               |  Retorna os endereços cadastrados     | 🔐 |
| <span style="color: rgb(255, 228, 126)">POST</span>   | /api/v1/address/store-full-data                   | Cadastro com todos os dados do endereço  | 🔐 |
| <span style="color: rgb(255, 228, 126)">POST</span> | /api/v1/address/store-by-cep                  | Cadastro com dados a partir do CEP informado   | 🔐 |
| <span style="color: rgb(107, 221, 154)">GET</span> | /api/v1/address/show-{address_id}-address            | Exibe dados do endereço por ID do endereço| 🔐 |
| <span style="color: rgb(107, 221, 154)">GET</span> | /api/v1/address/consult-cep/{cep}                              | Consulta rápida do CEP, sem gravar | 🔐 |
| <span style="color: rgb(116, 174, 246);">PUT</span> | /api/v1/address/update/{address_id}                            | Alterar dados do endereço por ID| 🔐 |
| <span style="color: rgb(247, 154, 142);">DELETE</span>   | /api/v1/address/remove/{address_id}                  | Deleta o endereço por ID | 🔐 |

### Obs:
- ✅ A rota para cadastro com dados completo do endereço: `/api/v1/address/store-full-data` pode ser utilizada para casos onde o CEP consultado não tem dados atrelado a ele e ou dados insuficientes!
- 🚧 A rota de update *NÃO VAI* fazer levar o campo de `CEP` e nem fazer uma nova consulta, e sim vai fazer a alteração apenas dos campos que são informados na requisiçõa, e de um campo na coluna `was_edited = true`, para os casos onde o usuário precisa a alteração de um campo do cadastro do endereço e que esse campo não esteja necessariamente atrelado ao CEP originalmente informado e evitando eventuais problemas referente a ajustes manuais no endereço já cadastrado. 🚧

## 👨🏼‍💻 Como executar

- Primeiros passos

```bash
# Instalação geral dos pacotes do projeto
composer install

# Necessário para carregar os arquivos auxiliar do projeto: app\Helpers\api & app\Helpers\utils
composer dump-autoload

# Limpando o cache das rotas

php artisan route:ca # ca alias para cache
php artisan config:ca # ca alias para cache
php artisan config:cle # ca alias para clear

# Iniciando nosso servidor

php artisan serve

```

### 🛢️ Banco de dados

- O banco de dados utilizado foi do PostgreSQl, assim sendo um banco online gerenciado no **<a href="https://supabase.com/">Supabase</a>**;

## 🔧 Outros

- 🔄 Com a padronização dos returns, o tratamento/processamento dos dados para o frontend que vier a utilizar desta API se torna muito mais fácil:

```json
{
    "success": true,
    "message": "Endereço cadastrado com sucesso!",
    "data": {
        "address_id": "05164e99-0fcc-40f9-9af3-bc6abfdef477",
        "cep": "########",
        "state": "##",
        "city": "########",
        "neighborhood": "########",
        "street": "########",
        "service": "open-cep",
        "longitude": "########",
        "latitude": "########",
        "updated_at": "2026-01-04T17:35:38.000000Z",
        "created_at": "2026-01-04T17:35:38.000000Z",
        "id": 1
    },
    "status": 201
}
```

- ❌

```json
{
    "success": false,
    "message": "O CEP é obrigatório!",
    "data": {
        "cep": [
            "O CEP é obrigatório!"
        ]
    },
    "status": 422
}
```

- Dentro da padronização, existe uma camada de auxílio localizado nos caminhos `app\Messages`, onde possui mensagens padronizadas para as mensagens de retorno da Request, por exemplo:

```php
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

```

- 📜 Para facilitar, dentro do diretório `json\*`, possui alguns .json prontos para as requisições.
