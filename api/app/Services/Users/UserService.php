<?php

namespace App\Services\Users;

use App\DTO\User\UserDTO;
use App\Repositories\Interfaces\User\UserContract;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function __construct(
        protected UserContract $userRepository
    ){}

    public function store(UserDTO $data)
    {
        return DB::transaction(fn() => $this->userRepository->store($data));
    }

    public function findByEmail(string $email)
    {
        return $this->userRepository->findByEmail($email);
    }

    public function findById(string $id)
    {
        $user = $this->userRepository->findById($id);

        if(!$user)
        {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Usuário não localizado pelo ID repassado');
        }

        return $user;
    }
}