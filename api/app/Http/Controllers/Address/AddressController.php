<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\Address\AddressFullDataRequest;
use App\Http\Requests\Address\AddressByCepRequest;
use App\Services\Address\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct(
        protected AddressService $addressService
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(string $userId)
    {
        return apiSuccess('Todos os endereços cadastrados', $this->addressService->index($userId));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeFullData(AddressFullDataRequest $req)
    {
        return apiSuccess('Endereço cadastrado com sucesso!', $this->addressService->storeFullData($req->validate()), true, 201);
        
    }

    public function storeByCep(AddressByCepRequest $req)
    {
        return apiSuccess('Endereço cadastrado com sucesso!', $this->addressService->storeByCep($req->validated()), true, 201);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $userId, string $addressId)
    {
        return apiSuccess('Dados do endereço', $this->addressService->show($userId, $addressId));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressFullDataRequest $req, string $addressId)
    {
        return apiSuccess('Endereço alterado com sucesso!', $this->addressService->update($req->validated(), $addressId));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $userId, string $addressId)
    {
        return apiSuccess('Endereço deletado com sucesso!', $this->addressService->destroy($userId, $addressId));
    }

    public function speedFetch(string $cep)
    {
        return apiSuccess('Consulta rápida para esse CEP', $this->addressService->fetchApi(formatCEP($cep)));
    }

    public function setMainAddress(string $userId, string $addressId)
    {
        return apiSuccess('Endereço definido como primário', $this->addressService->setMainAddress($userId, $addressId));
    }
}
