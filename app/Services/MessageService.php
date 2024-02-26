<?php

namespace App\Services;

use App\Exceptions\ApplicationException;
use App\Helpers\MessageHelper;
use App\Models\Message;

class MessageService
{
    private Message $model;

    private MessageStatusService $messageStatusService;

    public function __construct()
    {
        $this->model = new Message();
        $this->messageStatusService = new MessageStatusService();
    }

    /**
     * Create message
     *
     * @throws ApplicationException
     */
    public function createTransferReceivedMessage($user, $order): void
    {
        $this->model->create([
            'user_id' => $user,
            'order_id' => $order,
            'status_id' => $this->messageStatusService->getPendingStatusId(),
            'message' => MessageHelper::TRANSFER_RECEIVED,
        ]);
    }

    /**
     * Send pending messages
     *
     * @throws ApplicationException
     */
    public function sendMessage(): void
    {
        $pendingMessages = $this->model->where('status_id', $this->messageStatusService->getPendingStatusId())->get();

        foreach ($pendingMessages as $message) {
            if ($this->send()) {
                $this->model->where('id', $message->id)
                    ->update(['status_id' => $this->messageStatusService->getSentStatusId()]);
            } else {
                $this->model->where('id', $message->id)
                    ->update(['status_id' => $this->messageStatusService->getFailStatusId()]);
            }
        }
    }

    /**
     * Verify authorization
     */
    private function send(): bool
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://run.mocky.io/v3/54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);
        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        return $response->message;
    }
}
