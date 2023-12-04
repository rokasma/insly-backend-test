<?php

namespace App\Services\Api;

use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterService
{
    public function __construct(
        private readonly UserDetailService $userDetailService,
    ) {
    }

    public function createUser(RegisterRequest $request): void
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->address) {
            $this->userDetailService->create($user, $request->address);
        }
    }
}
