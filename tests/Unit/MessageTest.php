<?php

namespace Tests\Unit;

use App\Exceptions\ApplicationException;
use App\Services\MessageService;
use App\Services\MessageStatusService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MessageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MessageService();
        $this->messageStatusService = new MessageStatusService();
        (new DatabaseSeeder)->run();
    }

    /**
     * Validate status by id successfully.
     *
     * @throws ApplicationException
     */
    public function testSendMessage()
    {
        $pendingStatus = $this->messageStatusService->getPendingStatusId();
        $this->post(route('order.create'), [
            'value' => 10,
            'payer' => 1,
            'payee' => 2,
        ]);

        $this->service->sendMessage();

        $pendingMessages = DB::table('messages')
            ->select('id')
            ->where('status_id', $pendingStatus)
            ->count();
        $this->assertEquals(0, $pendingMessages);
    }

    /**
     * Validate status by id successfully.
     *
     * @throws ApplicationException
     */
    public function testCreateMessage()
    {
        $this->expectNotToPerformAssertions();
        $order = $this->post(route('order.create'), [
            'value' => 10,
            'payer' => 1,
            'payee' => 2,
        ]);

        $this->service->createTransferReceivedMessage(2, $order['id']);
    }
}
