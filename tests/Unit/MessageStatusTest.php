<?php

namespace Tests\Unit;

use App\Exceptions\ApplicationException;
use App\Services\MessageStatusService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

class MessageStatusTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MessageStatusService();
        (new DatabaseSeeder)->run();
    }

    public function testGetPendingStatusId()
    {
        $status = $this->service->getPendingStatusId();
        $this->assertIsInt($status);
    }

    public function testGetSentStatusId()
    {
        $status = $this->service->getSentStatusId();
        $this->assertIsInt($status);
    }

    public function testGetFailStatusId()
    {
        $status = $this->service->getFailStatusId();
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
