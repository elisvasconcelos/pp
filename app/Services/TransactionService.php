<?php

namespace App\Services;

use App\Exceptions\ApplicationException;
use App\Models\Transaction;

class TransactionService
{
    private Transaction $model;

    private UserService $userService;

    public function __construct()
    {
        $this->model = new Transaction();
        $this->userService = new UserService();
    }

    /**
     * Create Debit Transaction
     *
     * @throws ApplicationException
     */
    public function createDebitTransaction($userId, $amount, $orderId): void
    {
        $user = $this->userService->getPayee($userId);
        $this->userService->verifyBalance($userId, $amount);

        $newBalance = $user->balance - $amount;
        $this->model->create([
            'user_id' => $userId,
            'amount' => -$amount,
            'order_id' => $orderId,
            'balance' => $newBalance,
        ]);
        $this->userService->updateBalance($userId, $newBalance);
    }

    /**
     * Create Credit Transaction
     *
     * @throws ApplicationException
     */
    public function createCreditTransaction($userId, $amount, $orderId): void
    {
        $user = $this->userService->getPayee($userId);
        $newBalance = $user->balance + $amount;
        $this->model->create([
            'user_id' => $userId,
            'amount' => $amount,
            'order_id' => $orderId,
            'balance' => $newBalance,
        ]);
        $this->userService->updateBalance($userId, $newBalance);
    }

    /**
     * Create Return Transaction
     *
     * @throws ApplicationException
     */
    public function createReturnTransaction($userId, $orderId): void
    {
        $transaction = $this->model->where('order_id', $orderId)->where('user_id', $userId)->first();
        $user = $this->userService->getPayee($userId);

        $newBalance = $user->balance + abs($transaction->amount);
        $this->model->create([
            'user_id' => $userId,
            'amount' => abs($transaction->amount),
            'order_id' => $orderId,
            'balance' => $newBalance,
        ]);
        $this->userService->updateBalance($userId, $newBalance);
    }
}
