<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function store(TransactionRequest $request): JsonResponse
    {
        return response()
            ->json(
                $this->service->store(
                    $request->user(),
                    $request->validated()
                ),
                200
            );
    }
}
