<?php

namespace App\DTO\Address\api;

class AddressApiDTO
{
    public string $cep;
    public string $state;
    public string $city;
    public string $neighborhood;
    public string $street;
    public string $service;
    public array $location;

    public function __construct(
        string $cep,
        string $state,
        string $city,
        string $neighborhood,
        string $street,
        string $service,
        array $location       
    ){
        $this->cep = $cep;
        $this->state = $state;
        $this->city = $city;
        $this->neighborhood = $neighborhood;
        $this->street = $street;
        $this->service = $service;
        $this->location = $location;
    }
}