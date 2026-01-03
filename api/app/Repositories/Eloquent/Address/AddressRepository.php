<?php

namespace App\Repositories\Eloquent\Address;

use App\DTO\Address\api\AddressApiDTO;
use App\Models\Address\AddressModel;
use App\Repositories\Interfaces\Address\AddressContract;
use App\Repositories\Interfaces\User\UserContract;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class AddressRepository implements AddressContract
{
    public function __construct(
        protected UserContract $userRepository
    ){}

    public function index(string $userId): Collection
    {
        return AddressModel::query()
                            ->where('user_id', $userId)
                            ->get();
    }

    public function getLastUuId(string $userId, string $addressId): bool
    {
        return AddressModel::query()
                            ->where('user_id', $userId)
                            ->where('address_id', $addressId)
                            ->exists();
    }

    public function store(AddressApiDTO $data): AddressModel
    {
        $user = $this->userRepository->findById($data->user_id);

        return AddressModel::create([
            'address_id' => $data->address_api,
            'user_id' => $user->id,
            'user' => $user->name, 
            'cep' => $data->cep,
            'state' => $data->state, 
            'city' => $data->city, 
            'neighborhood' => $data->neighborhood, 
            'street' => $data->street,  
            'service' => $data->service, 
            'longitude' => $data->location['coordinates']['longitude'] ?? 'Sem longitude informada', 
            'latitude' => $data->location['coordinates']['latitude'] ?? 'Sem longitude latitude', 

        ]);
    }

    public function findById(string $userId, string $addressId): ?AddressModel
    {
        return AddressModel::query()
                            ->where('user_id', $userId)
                            ->where('address_id', $addressId)
                            ->first();
    }

    /**
     * @var AddressApiDTO $data
     */
    public function update(AddressApiDTO $data, string $addressId): AddressModel
    {
        $address = $this->findById($data->user_id, $addressId);

        Log::debug('Dados: ', [
            'data' => $data
        ]);

        $address->update([
            'cep' => $data->cep,
            'state' => $data->state, 
            'city' => $data->city, 
            'neighborhood' => $data->neighborhood, 
            'street' => $data->street,  
            'service' => $data->service ?? $address->service, 
            'longitude' => $data->location['coordinates']['longitude'], 
            'latitude' => $data->location['coordinates']['latitude'], 
            'was_edited' => true
        ]);

        $address->save();

        return $address;
    }

    public function setMainAddress(string $userId, string $addressId): AddressModel
    {
        $address = $this->findById($userId, $addressId);

        $address->update([
            'is_main_address' => 1
        ]);
        $address->save();

        return $address;
    }

    public function destroy(string $userId, string $addressId)
    {
        $address = $this->findById($userId, $addressId);
        
        $address->delete();

        return;
    }
}