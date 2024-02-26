<?php

namespace Tests\Unit;

use App\Exceptions\ApplicationException;
use App\Services\UserTypeService;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

class UserTypeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserTypeService();
        (new DatabaseSeeder)->run();
    }

    public function testGetUserTypeSuccessfully(): void
    {
        $type = $this->service->getUserTypeById(1);
        $this->assertIsString($type->name);

    }

    public function testGetUserTypeFailed(): void
    {
        $this->expectException(ApplicationException::class);
        $this->service->getUserTypeById(6);
    }
}
