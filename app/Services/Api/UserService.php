<?php

namespace App\Services\Api;

use App\Http\Requests\Api\UserUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        private readonly UserDetailService $userDetailService,
    ) {
    }

    public function delete(User $user): ?bool
    {
        return $user->delete();
    }

    public function update(UserUpdateRequest $request, User $user): void
    {
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $request->address
            ? $this->userDetailService->updateOrCreate($user, $request->address)
            : $this->userDetailService->delete($user);
    }
}
