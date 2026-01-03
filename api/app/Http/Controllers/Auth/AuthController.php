<?php

namespace App\Http\Controllers\Auth;

use App\DTO\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Messages\Auth\Request\AuthDefaultMessages;
use App\Services\Users\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function register(AuthRequest $req)
    {   
        $data = $req->validated();

        $userDTO = new UserDTO(
            $data['name'],
            $data['email'],
            $data['password'],
        );

        return apiSuccess('Usuário cadastrado com sucesso!', $this->userService->store($userDTO));
    }

    public function login(Request $req)
    {
        $data = $req->validate([
            'email' => 'required|email',
            'password' => 'required'

        ], [
            'email.required' => AuthDefaultMessages::EMAIL_REQUIRED->value,
            'email.email' => AuthDefaultMessages::EMAIL_STRING_FORMAT->value,
            'password.required' => AuthDefaultMessages::PASSWORD_REQUIRED->value

        ]);

        $user = $this->userService->findByEmail($data['email']);

        if(!$user)
        {
            throw ValidationException::withMessages([
                'message' => 'Credenciais incorretas.'
            ]);
        }
        
        if($user && !Hash::check($data['password'], $user->password))
        {
            throw ValidationException::withMessages([
                'message' => 'Credenciais incorretas.'
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login bem sucedido!',
            'token' => $token,
            'user' => $user
            
        ], 200);
    }

    public function logout(Request $req) 
    {
        $req->user()->CurrentAccessToken()->delete();
        return apiSuccess('Logout bem sucedido!');
    }
}
