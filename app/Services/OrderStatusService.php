<?php

namespace App\Services;

use App\Exceptions\ApplicationException;
use App\Helpers\MessageHelper;
use App\Models\OrderStatus;

class OrderStatusService
{
    private OrderStatus $model;

    public function __construct()
    {
        $this->model = new OrderStatus();
    }

    /**
     * Get Status Pending
     *
     * @throws ApplicationException
     */
    public function getPendingStatusId()
    {
        return $this->getStatusIdByName('pending');
    }

    /**
     * Get Status Cancel
     *
     * @throws ApplicationException
     */
    public function getCanceledStatusId()
    {
        return $this->getStatusIdByName('canceled');
    }

    /**
     * Get Status Unauthorized
     *
     * @throws ApplicationException
     */
    public function getUnauthorizedStatusId()
    {
        return $this->getStatusIdByName('unauthorized');
    }

    /**
     * Get Status Complete
     *
     * @throws ApplicationException
     */
    public function getCompleteStatusId()
    {
        return $this->getStatusIdByName('complete');
    }

    /**
     * Get Status Id by Name
     *
     * @throws ApplicationException
     */
    public function getStatusIdByName($name)
    {
        $status = $this->model->where('name', $name)->first();
        if (! isset($status->id)) {
            throw new ApplicationException(MessageHelper::STATUS_NOT_FOUND, 412);
        }

        return $status->id;
    }
}
