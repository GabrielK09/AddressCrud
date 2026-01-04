<?php

namespace App\Repositories\Eloquent\Address;

use App\DTO\Address\api\AddressApiDTO;
use App\Models\Address\AddressModel;
use App\Repositories\Interfaces\Address\AddressContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AddressRepository implements AddressContract
{
    public function index(): Collection
    {
        return AddressModel::query()
                            ->get();
    }

    public function store(AddressApiDTO $data): AddressModel
    {
        return AddressModel::create([
            'cep' => $data->cep,
            'state' => $data->state, 
            'city' => $data->city, 
            'neighborhood' => $data->neighborhood, 
            'street' => $data->street,  
            'service' => $data->service, 
            'longitude' => $data->location['longitude'], 
            'latitude' => $data->location['latitude'], 

        ]);
    }

    public function findById(string $addressId): ?AddressModel
    {
        return AddressModel::findOrFail($addressId)->first();
    }

    /**
     * @var AddressApiDTO $data
     */
    public function update(AddressApiDTO $data, string $addressId): AddressModel
    {
        $address = $this->findById($addressId);

        Log::debug('Dados: ', [
            'data' => $data
        ]);

        $address->update([
            'state' => $data->state, 
            'city' => $data->city, 
            'neighborhood' => $data->neighborhood, 
            'street' => $data->street,  
            'service' => $data->service ?? $address->service, 
            'longitude' => $data->location['longitude'], 
            'latitude' => $data->location['latitude'], 
            'was_edited' => true
        ]);

        $address->save();

        return $address;
    }

    public function destroy(string $addressId): void
    {
        $address = $this->findById($addressId);
        
        $address->delete();
    }
}