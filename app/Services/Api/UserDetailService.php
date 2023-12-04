<?php

namespace App\Services\Api;

use App\Models\User;
use App\Models\UserDetail;
use App\Repositories\Api\UserDetailRepository;

class UserDetailService
{
    public function __construct(
        private readonly UserDetailRepository $detailRepository,
    ) {
    }

    public function create(User $user, string $address): void
    {
        $userDetail = new UserDetail();
        $userDetail->user_id = $user->id;
        $userDetail->address = $address;
        $userDetail->save();
    }

    public function updateOrCreate(User $user, string $address): void
    {
        $userDetail = $this->detailRepository->findByUserId($user->id);

        if (!$userDetail) {
            $this->create($user, $address);
        }

        $userDetail->address = $address;
        $userDetail->save();
    }

    public function delete(User $user): void
    {
        $userDetail = $this->detailRepository->findByUserId($user->id);
        $userDetail?->delete();
    }
}
