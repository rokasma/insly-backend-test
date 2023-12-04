<?php

namespace App\Repositories\Api;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function __construct(
        private readonly User $user,
    ){
    }

    public function findByEmail(string $email): ?User
    {
        return $this->user
            ->newQuery()
            ->where('email', '=', $email)
            ->first();
    }

    public function list(): Collection
    {
        return $this->user
            ->newQuery()
            ->with('details')
            ->get();
    }
}
