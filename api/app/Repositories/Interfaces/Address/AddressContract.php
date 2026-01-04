<?php

namespace App\Repositories\Interfaces\Address;

use App\DTO\Address\api\AddressApiDTO;
use App\Models\Address\AddressModel;
use Illuminate\Support\Collection;

interface AddressContract
{
    public function index(string $userId): Collection;
    public function getLastUuId(string $userId, string $addressId): bool;
    public function store(AddressApiDTO $data): AddressModel;
    public function findById(string $userId, string $addressId): ?AddressModel;
    public function update(AddressApiDTO $data, string $addressId): AddressModel;
    public function destroy(string $userId, string $addressId);
}