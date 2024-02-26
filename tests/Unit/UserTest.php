<?php

namespace Tests\Unit;

use App\Exceptions\ApplicationException;
use App\Services\UserService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserService();
        (new DatabaseSeeder)->run();
    }

    public function testGetPayeeSuccessfully(): void
    {
        $user = $this->service->getPayee(1);
        $this->assertIsString($user->name);
    }

    public function testGetPayeeFailed(): void
    {
        $this->expectException(ApplicationException::class);
        $this->service->getPayee(10);
    }

    public function testGetPayerSuccessfully(): void
    {
        $user = $this->service->getPayer(1);
        $this->assertIsString($user->name);
    }

    public function testGetPayerFailed(): void
    {
        $this->expectException(ApplicationException::class);
        $this->service->getPayer(4);
    }

    public function testVerifyBalanceSuccessfully(): void
    {
        $this->expectNotToPerformAssertions();
        $this->service->verifyBalance(1, 10);
    }

    public function testVerifyBalanceFailed(): void
    {
        $this->expectException(ApplicationException::class);
        $this->service->verifyBalance(1, 5000);
    }
}
