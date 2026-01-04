<?php

namespace App\Services\Address;

use App\DTO\Address\api\AddressApiDTO;
use App\Repositories\Interfaces\Address\AddressContract;
use App\Exceptions\NotFoundExceptions\Address\AddressNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class AddressService
{
    public function __construct(
        private string $apiUrl,
        protected AddressContract $addressRepository
    ){}

    public function index()
    {
        return $this->addressRepository->index();
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
                        ->timeout(30)
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
            $coordinates = [
                'longitude' => $data['longitude'] ?? 'Sem longitude informada',
                'latitude' => $data['latitude'] ?? 'Sem latitude informada',

            ];

            $cepData = new AddressApiDTO(
                cep: formatCEP($data['cep']),
                state: $data['state'],
                city: $data['city'],
                neighborhood: $data['neighborhood'],
                street: $data['street'],
                service: '',
                location: $coordinates,
            );

            return DB::transaction(fn() => $this->addressRepository->store($cepData));
        } catch (\Throwable $th) {
            throw new \RuntimeException('Erro ao salvar os dados: ' . $th->getMessage());            
        }
    }

    public function storeByCep(array $data)
    {
        try {
            $fetchApiCep = $this->fetchApi($data['cep']);

            $cepData = new AddressApiDTO(
                cep: $fetchApiCep['cep'],
                state: $fetchApiCep['state'],
                city: $fetchApiCep['city'],
                neighborhood: $fetchApiCep['neighborhood'],
                street: $fetchApiCep['street'],
                service: $fetchApiCep['service'],
                location: $fetchApiCep['location']['coordinates']
            );  
            
            return $this->addressRepository->store($cepData);
        } catch (\Throwable $th) {
            throw new \RuntimeException('Erro ao salvar os dados: ' . $th->getMessage());
            
        }
    }

    public function show(string $addressId)
    {
        try {
            $address = $this->addressRepository->findById($addressId);

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
        $address = $this->show($addressId);

        if(!$address)
        {
            throw new AddressNotFoundException('Endereço não localizado!!');
        }

        $location = [
            'coordinates' => [
                'longitude' => $data['longitude'] ?? $address->longitude,
                'latitude' => $data['latitude'] ?? $address->latitude
            ]
        ];

        $cepData = new AddressApiDTO(
            cep: '',
            state: $data['state'] ?? $address->state,
            city: $data['city'] ?? $address->city,
            neighborhood: $data['neighborhood'] ?? $address->neighborhood,
            street: $data['street'] ?? $address->street,
            service: $address->service,
            location: $location['coordinates']
        );

        return DB::transaction(fn() => $this->addressRepository->update($cepData, $addressId));
    }

    public function destroy(string $addressId)
    {
        $this->show($addressId);
        
        return $this->addressRepository->destroy($addressId);
    }
}