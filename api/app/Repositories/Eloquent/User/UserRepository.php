<?php

namespace App\Repositories\Eloquent\User;

use App\DTO\User\UserDTO;
use App\Models\User;
use App\Repositories\Interfaces\User\UserContract;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserContract
{
    public function store(UserDTO $userData): User
    {
        return User::create([
            'name' => $userData->name,
            'email' => $userData->email,
            'password' => Hash::make($userData->password),
        ]);
    }

    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function findById(string $id): User
    {
        return User::query()
                    ->where('id', $id)
                    ->select('id', 'name', 'email')
                    ->first();
    }

    public function update(UserDTO $data, string $userId)
    {
        $user = $this->findById($userId);

        $user->update([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password)
        ]);
        
        $user->save(); 

        return $user;
    }
}