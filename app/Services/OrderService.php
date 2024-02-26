<?php

namespace App\Services;

use App\Exceptions\ApplicationException;
use App\Helpers\MessageHelper;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Throwable;

class OrderService
{
    private Order $model;

    private UserService $userService;

    private TransactionService $transactionService;

    private OrderStatusService $orderStatusService;

    private MessageService $messageService;

    public function __construct()
    {
        $this->model = new Order();
        $this->userService = new UserService();
        $this->transactionService = new TransactionService();
        $this->orderStatusService = new OrderStatusService();
        $this->messageService = new MessageService();
    }

    /**
     * Stores transaction
     *
     * @throws ApplicationException
     */
    public function store($transactionData): array
    {
        if ($transactionData['payer'] == $transactionData['payee']) {
            throw new ApplicationException(MessageHelper::PAYER_EQUALS_PAYEE, 422);
        }

        $payer = $this->userService->getPayer($transactionData['payer']);
        $payee = $this->userService->getPayee($transactionData['payee']);
        $this->userService->verifyBalance($payer->id, $transactionData['value']);
        $orderId = $this->createOrder([
            'payer' => $payer->id,
            'payee' => $payee->id,
            'amount' => $transactionData['value'],
            'status_id' => $this->orderStatusService->getPendingStatusId(),
        ]);

        $this->createDebitTransaction($payer->id, $transactionData['value'], $orderId);
        if (! $this->isAuthorized()) {
            $this->transactionService->createReturnTransaction($payer->id, $orderId);
            $this->setOrderStatusUnauthorized($orderId);
            throw new ApplicationException(MessageHelper::ORDER_UNAUTHORIZED, 401);
        }

        $this->createCreditTransaction($payee->id, $payer->id, $transactionData['value'], $orderId);
        $this->setOrderStatusComplete($orderId);
        $this->messageService->createTransferReceivedMessage($payee->id, $orderId);

        return [
            'message' => MessageHelper::ORDER_STORE_SUCCESS,
            'id' => $orderId,
        ];
    }

    private function createOrder($payload)
    {
        try {
            DB::beginTransaction();
            $order = $this->model->create($payload);
            DB::commit();

            return $order->id;
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            response()->json(['message' => MessageHelper::ORDER_THROWABLE], 500);
        }
    }

    private function createDebitTransaction($userId, $amount, $orderId): void
    {
        try {
            DB::beginTransaction();
            $this->transactionService->createDebitTransaction($userId, $amount, $orderId);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            $this->setOrderStatusCanceled($orderId);

            response()->json(['message' => MessageHelper::ORDER_THROWABLE], 500);

            return;
        }
    }

    /**
     * @throws ApplicationException
     */
    private function createCreditTransaction($userId, $payerId, $amount, $orderId): void
    {
        try {
            DB::beginTransaction();
            $this->transactionService->createCreditTransaction($userId, $amount, $orderId);
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            $this->transactionService->createReturnTransaction($payerId, $orderId);
            $this->setOrderStatusCanceled($orderId);
            throw new ApplicationException(MessageHelper::ORDER_THROWABLE, 500);
        } finally {
            DB::commit();
        }
    }

    /**
     * Set Order Status as Canceled
     */
    private function setOrderStatusCanceled($orderId): void
    {
        try {
            DB::beginTransaction();
            $this->model->where('id', $orderId)->update(['status' => $this->orderStatusService->getCanceledStatusId()]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            response()->json(['message' => MessageHelper::ORDER_THROWABLE], 500);

            return;
        }
    }

    /**
     * Set Order Status as Unauthorized
     */
    private function setOrderStatusUnauthorized($orderId): void
    {
        try {
            DB::beginTransaction();
            $this->model->where('id', $orderId)->update(['status' => $this->orderStatusService->getUnauthorizedStatusId()]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            response()->json(['message' => MessageHelper::ORDER_THROWABLE], 500);

            return;
        }
    }

    /**
     * Set Order Status as Complete
     */
    private function setOrderStatusComplete($orderId): void
    {
        try {
            DB::beginTransaction();
            $this->model->where('id', $orderId)->update(['status' => $this->orderStatusService->getCompleteStatusId()]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            report($e);
            response()->json(['message' => MessageHelper::ORDER_THROWABLE], 500);

            return;
        }
    }

    /**
     * Verify authorization
     */
    private function isAuthorized(): bool
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://run.mocky.io/v3/5794d450-d2e2-4412-8131-73d0293ac1cc',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);
        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        return $response->message == 'Autorizado';
    }
}
