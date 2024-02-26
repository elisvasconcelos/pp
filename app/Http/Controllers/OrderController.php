<?php

namespace App\Http\Controllers;

use App\Exceptions\ApplicationException;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OrderController extends Controller
{
    private OrderService $service;

    public function __construct(OrderService $service)
    {
        $this->service = $service;
    }

    /**
     *  Stores transaction
     *
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'payer' => 'required|integer',
                    'payee' => 'required|integer',
                    'value' => 'required|decimal:0,2|gt:0',
                ]
            );
            if ($validator->fails()) {
                throw new ApplicationException($validator->errors(), 422);
            }

            $data = [
                'payer' => $request->payer,
                'payee' => $request->payee,
                'value' => $request->value,
            ];
            $response = $this->service->store($data);

            return response()->json($response, 201);
        } catch (ApplicationException $e) {
            report($e);

            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
