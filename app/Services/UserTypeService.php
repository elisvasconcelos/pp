<?php

namespace App\Services;

use App\Exceptions\ApplicationException;
use App\Helpers\MessageHelper;
use App\Models\UserType;

class UserTypeService
{
    private UserType $model;

    public function __construct()
    {
        $this->model = new UserType();
    }

    /**
     * Returns the type description by ID
     *
     * @throws ApplicationException
     */
    public function getUserTypeById($typeId): UserType
    {
        $userType = $this->model->where('id', $typeId)->first();
        if (! isset($userType->id)) {
            throw new ApplicationException(MessageHelper::USER_TYPE_NOT_FOUND, 412);
        }

        return $userType;
    }
}
