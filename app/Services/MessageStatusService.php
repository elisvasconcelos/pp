<?php

namespace App\Services;

use App\Exceptions\ApplicationException;
use App\Helpers\MessageHelper;
use App\Models\MessageStatus;

class MessageStatusService
{
    private MessageStatus $model;

    public function __construct()
    {
        $this->model = new MessageStatus();
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
     * Get Status Pending
     *
     * @throws ApplicationException
     */
    public function getSentStatusId()
    {
        return $this->getStatusIdByName('sent');
    }

    /**
     * Get Status Pending
     *
     * @throws ApplicationException
     */
    public function getFailStatusId()
    {
        return $this->getStatusIdByName('fail');
    }

    /**
     * Get Status Pending
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
