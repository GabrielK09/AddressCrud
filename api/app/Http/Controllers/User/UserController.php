<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Services\Users\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct(
        protected UserService $userService
    ){}

    public function show(string $id)
    {
        return apiSuccess('Dados do usuário selecionado', $this->userService->findById($id));
    }

    public function update(AuthRequest $request, string $id)
    {
        return apiSuccess('Dados do usuário alterado com sucesso!', $this->userService->update($request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
