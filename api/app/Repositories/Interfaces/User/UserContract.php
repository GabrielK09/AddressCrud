<?php

namespace App\Repositories\Interfaces\User;

use App\DTO\User\UserDTO;
use App\Models\User;

interface UserContract 
{
    public function store(UserDTO $data): User;
    public function findByEmail(string $email): ?User;
    public function findById(string $id): User;
}