<?php

namespace App\Services\Address;

use App\DTO\Address\api\AddressApiDTO;
use App\Repositories\Interfaces\Address\AddressContract;
use App\Exceptions\NotFoundExceptions\Address\AddressNotFoundException;
use App\Exceptions\NotFoundExceptions\User\UserNotFoundException;
use App\Repositories\Interfaces\User\UserContract;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class AddressService
{
    public function __construct(
        private string $apiUrl,
        protected AddressContract $addressRepository
    ){}

    public function index(string $userId)
    {
        return $this->addressRepository->index($userId);
    }

    private function generateUuid(): string
    {
        return (string) Str::uuid();
    }

    private function generateUniqueUuid(string $userId): string
    {   
        do {
            $uuid = $this->generateUuid();
            
        } while ($this->addressRepository->getLastUuId($userId, $uuid));

        return $uuid;
    }

    public function fetchApi(string $cep)
    {
        /**
         * Tipagem necessária por conta de que pela IDE os métodos não ficam acessiveis
         * @var Response $res
         */
        $res = Http::withoutVerifying()
                        ->withHeaders(
                        [
                            'Accept' => 'application/json', 
                            'Content-Type' => 'application/json'
                        ]
                        )
                        ->withOptions(['verify' => false])
                        ->get($this->apiUrl . "/{$cep}");

        if($res->failed())
        {
            throw new \RuntimeException('Erro na consulta: ' . $res);
        }

        return $res->json();
    }

    public function storeFullData(array $data)
    {
        try {
            $uuid = $this->generateUniqueUuid($data['user_id']);

            $coordinates = [
                'longitude' => $data['longitude']  ?? 'Sem longitude informada',
                'latitude' => $data['latitude'] ?? 'Sem latitude informada',

            ];

            $cepData = new AddressApiDTO(
                $uuid,
                $data['user_id'],
                $data['cep'],
                $data['state'],
                $data['city'],
                $data['neighborhood'],
                $data['street'],
                '',
                $coordinates,
            );

            Log::debug('storeFullData', [
                'data' => $cepData,
                'location' => $cepData->location['longitude'],
                'uuid' => $uuid
            ]);

            return DB::transaction(fn() => $this->addressRepository->store($cepData));
        } catch (\Throwable $th) {
            throw new \RuntimeException('Erro ao salvar os dados: ' . $th->getMessage());            
        }
    }

    public function storeByCep(array $data)
    {
        try {
            $uuid = $this->generateUniqueUuid($data['user_id']);
            $fetchApiCep = $this->fetchApi($data['cep']);

            $cepData = new AddressApiDTO(
                $uuid,
                $data['user_id'],
                $fetchApiCep['cep'],
                $fetchApiCep['state'],
                $fetchApiCep['city'],
                $fetchApiCep['neighborhood'],
                $fetchApiCep['street'],
                $fetchApiCep['service'],
                $fetchApiCep['location']['coordinates']
            );  
            
            Log::debug('storeByCep', [
                'type' => $cepData,
                'location' => $cepData->location,
                'uuid' => $uuid
            ]);

            return $this->addressRepository->store($cepData);
        } catch (\Throwable $th) {
            throw new \RuntimeException('Erro ao salvar os dados: ' . $th->getMessage());
            
        }
    }

    public function show(string $userId, string $addressId)
    {
        try {
            $address = $this->addressRepository->findById($userId, $addressId);

            if(!$address)
            {
                throw new AddressNotFoundException('Endereço não localizado!');
            }

            return $address;
        } catch (QueryException $q) {
            $sqlState = $q->errorInfo[0];

            if($sqlState === '22P02')
            {
                throw new \App\Exceptions\QueryExceptions\QueryException('ID fora do padrão esperado!');
            }

            throw new \App\Exceptions\QueryExceptions\QueryException('Erro na consulta no banco de dados');
        }
    }

    public function update(array $data, string $addressId)
    {
        $address = $this->show($data['user_id'], $addressId);

        if(!$address)
        {
            throw new AddressNotFoundException('Endereço não localizado!!');
        }

        $location = [
            'coordinates' => [
                'longitude' => $data['longitude'],
                'latitude' => $data['latitude']
            ]
        ];

        $cepData = new AddressApiDTO(
            '',
            $data['user_id'],
            $data['cep'],
            $data['state'],
            $data['city'],
            $data['neighborhood'],
            $data['street'],
            $data['service'] ?? $address->service,
            $location['coordinates']
        );

        return DB::transaction(fn() => $this->addressRepository->update($cepData, $addressId));
    }

    public function destroy(string $userId, string $addressId)
    {
        $this->show($userId, $addressId);
        
        return $this->addressRepository->destroy($userId, $addressId);
    }
}