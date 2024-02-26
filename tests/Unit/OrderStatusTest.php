<?php

namespace Tests\Unit;

use App\Exceptions\ApplicationException;
use App\Services\OrderStatusService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new OrderStatusService();
        (new DatabaseSeeder)->run();
    }

    public function testGetPendingStatusId()
    {
        $status = $this->service->getPendingStatusId();
        $this->assertIsInt($status);
    }

    public function testGetCanceledStatusId()
    {
        $status = $this->service->getCanceledStatusId();
        $this->assertIsInt($status);
    }

    public function testGetUnauthorizedStatusId()
    {
        $status = $this->service->getUnauthorizedStatusId();
        $this->assertIsInt($status);
    }

    public function testGetCompleteStatusId()
    {
        $status = $this->service->getCompleteStatusId();
        $this->assertIsInt($status);
    }

    public function testGetStatusIdByNameSuccess()
    {
        $status = $this->service->getStatusIdByName('pending');
        $this->assertIsInt($status);
    }

    public function testGetStatusIdByNameFailed()
    {
        $this->expectException(ApplicationException::class);
        $this->service->getStatusIdByName('unknowing');
    }
}
