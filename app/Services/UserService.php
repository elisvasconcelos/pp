<?php

namespace App\Services;

use App\Exceptions\ApplicationException;
use App\Helpers\MessageHelper;
use App\Models\User;

class UserService
{
    private User $model;

    private UserTypeService $userTypeService;

    public function __construct()
    {
        $this->model = new User();
        $this->userTypeService = new UserTypeService();
    }

    /**
     * Get User by Id
     *
     * @throws ApplicationException
     */
    private function getUser($userId)
    {
        return $this->model->where('id', $userId)->first();
    }

    /**
     * Get Payee by Id
     *
     * @throws ApplicationException
     */
    public function getPayee($userId)
    {
        $user = $this->getUser($userId);

        if (! isset($user->id)) {
            throw new ApplicationException(MessageHelper::PAYEE_NOT_FOUND, 412);
        }

        return $user;
    }

    /**
     * Get Payer by Id
     *
     * @throws ApplicationException
     */
    public function getPayer($userId)
    {
        $user = $this->getUser($userId);
        if (! isset($user->id)) {
            throw new ApplicationException(MessageHelper::PAYER_NOT_FOUND, 412);
        }

        $type = $this->userTypeService->getUserTypeById($user->type_id);
        if (! $type->isPayer()) {
            throw new ApplicationException(MessageHelper::NOT_PAYER_TYPE, 412);
        }

        return $user;
    }

    /**
     * Verify user balance
     *
     * @throws ApplicationException
     */
    public function verifyBalance($userId, $amount): void
    {
        $user = $this->getUser($userId);

        if ($user->balance < $amount) {
            throw new ApplicationException(MessageHelper::UNAVAILABLE_BALANCE, 412);
        }
    }

    /**
     * Debits amount from user balance
     *
     * @throws ApplicationException
     */
    public function updateBalance($userId, $balance): void
    {
        $this->model->where('id', $userId)->update(['balance' => $balance]);
    }
}
