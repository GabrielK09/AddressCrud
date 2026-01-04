# 📌 Geral

### Intuito

```bash
# Dentro de nosso caminho api/, rode os seguintes comandos
composer install # Para instalação geral dos pacotes do laravel
composer dump-autoload
# Dentro do projeto existem dois caminhos destinados a auxilio geral, sendo o diretório de app/Helpers/*, o comando referente carrega esses caminhos permetindo utilizar as funções de apiSuccess e apiError de app/Helpers/api/apiResponse.php e o formatData, app/Helpers/utils/formatData.php

php artisan config:ca # ca será o alias para cache
php artisan config:cle # cle será o alias para clear
php artisan route:ca  # ca será o alias para cache
php artisan serve # para o start do servidor localmente
```

### Observação
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

## 🔐 Autenticação - AuthController
### O método de autenticação utilizada foi o do Laravel Sanctum!

| Método | Rota                                 | Descrição                                  | Auth |
|-------:|--------------------------------------|--------------------------------------------|:----:|
| POST   | /api/v1/auth/register                |  Destinado a criação do usuário da API     | ❌ |
| POST   | /api/v1/auth/login                   | Login e retorno do token Bearer de acesso  | ❌ |
| POST   | /api/v1/auth/logout                  | Será feito o logout do usuário da sessão   | ❌ |

## 🏠 Endereços - AddressController

| Método | Rota                                 | Descrição                                  | Auth |
|-------:|--------------------------------------|--------------------------------------------|:----:|
| GET    | /api/v1/address/index/{user_id}                |  Retorna os endereços cadastrados do usuário     | 🔐 |
| POST   | /api/v1/address/store-full-data                   | Cadastro com todos os dados do endereço  | 🔐 |
| POST | /api/v1/address/store-by-cep                  | Cadastro com dados a partir do CEP informado   | 🔐 |
| GET | /api/v1/address/show-{user_id}-{address_id}-address            | Exibe dados do endereço por usuário e ID do endereço| 🔐 |
| GET | /api/v1/address/consult-cep/{cep}                              | Consulta rápida do CEP, sem gravar | 🔐 |
| PUT | /api/v1/address/update/{address_id}                            | Alterar dados do endereço por ID| 🔐 |
| DELETE   | /api/v1/address/remove/{user_id}/{address_id}                  | Deleta o endereço por ID | 🔐 |


## Camadas

