<?php

namespace App\Repositories\Api;

use App\Models\UserDetail;

class UserDetailRepository
{
    public function __construct(private readonly UserDetail $userDetail)
    {
    }

    public function findByUserId(int $id): ?UserDetail
    {
        return $this->userDetail
            ->newQuery()
            ->where('user_id', '=', $id)
            ->first();
    }
}
