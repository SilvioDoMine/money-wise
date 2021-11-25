<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;

class TransactionController extends Controller
{
    protected TransactionService $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    /**
     * Endpoint para realização de uma transferência e/ou adição de créditos.
     *
     * @param TransactionRequest $request
     * @return JsonResponse
     */
    public function store(TransactionRequest $request): JsonResponse
    {
        $response = $this->service->store(
            $request->user(),
            $request->validated()
        );

        return response()
            ->json([
                'success' => $response,
                'message' => $response ?
                    'Sua transação foi iniciada. Nos te notificaremos quando ela for concluída. Você pode conferir o status de todas suas transações.' :
                    'Infelizmente usuários do tipo LOJA não pode realizar uma transação.',
            ]);
    }
}
