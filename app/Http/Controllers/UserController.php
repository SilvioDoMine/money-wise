<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;   
    }


    public function login(LoginRequest $request): JsonResponse
    {
        $response = $this->service->login(
            $request->validated()
        );

        return response()->json(
            $response,
            $response['success'] ?
                Response::HTTP_OK :
                Response::HTTP_UNAUTHORIZED
        );
    }
}
