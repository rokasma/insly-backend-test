<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Api\UserRepository;
use App\Services\Api\UserService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserService $userService,
    ) {
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        return $this->userService->update($request, $user);
    }

    public function list(): AnonymousResourceCollection
    {
        return UserResource::collection($this->userRepository->list());
    }

    public function delete(User $user): Response
    {
        $this->userService->delete($user);

        return response()->noContent();
    }
}
