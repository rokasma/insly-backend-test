<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Services\Api\RegisterService;
use Illuminate\Http\Response;

class RegisterController extends Controller
{
    public function __construct(
        private readonly RegisterService $registerService,
    ) {
    }

    public function register(RegisterRequest $request): Response
    {
        $this->registerService->createUser($request);

        return response()->noContent();
    }
}
