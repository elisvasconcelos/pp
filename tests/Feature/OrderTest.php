<?php

namespace Tests\Feature;

use App\Helpers\MessageHelper;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

class OrderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new DatabaseSeeder)->run();
    }

    /**
     * Create a credit transaction successfully.
     */
    public function testOrderSuccessfulResponse(): void
    {
        $this->post(route('order.create'), [
            'value' => 10,
            'payer' => 1,
            'payee' => 2,
        ])
            ->assertStatus(201)
            ->assertJson(['message' => MessageHelper::ORDER_STORE_SUCCESS]);
    }

    /**
     * Create a credit transaction successfully.
     */
    public function testOrderInvalidPayload(): void
    {
        $this->post(route('order.create'), [
            'amount' => 10,
            'payer' => 1,
            'payee' => 2,
        ])->assertStatus(422);
    }

    /**
     * Create a credit transaction successfully.
     */
    public function testOrderInvalidPayerType(): void
    {
        $this->post(route('order.create'), [
            'value' => 10,
            'payer' => 4,
            'payee' => 2,
        ])
            ->assertStatus(412)
            ->assertJson(['message' => MessageHelper::NOT_PAYER_TYPE]);
    }

    /**
     * Create a credit transaction successfully.
     */
    public function testOrderInvalidPayer(): void
    {
        $this->post(route('order.create'), [
            'value' => 10,
            'payer' => 20,
            'payee' => 2,
        ])
            ->assertStatus(412)
            ->assertJson(['message' => MessageHelper::PAYER_NOT_FOUND]);
    }

    /**
     * Create a credit transaction successfully.
     */
    public function testOrderInvalidPayee(): void
    {
        $this->post(route('order.create'), [
            'value' => 10,
            'payer' => 2,
            'payee' => 20,
        ])
            ->assertStatus(412)
            ->assertJson(['message' => MessageHelper::PAYEE_NOT_FOUND]);
    }

    /**
     * Create a credit transaction successfully.
     */
    public function testOrderUnavailableBalance(): void
    {
        $this->post(route('order.create'), [
            'value' => 1600,
            'payer' => 1,
            'payee' => 2,
        ])
            ->assertStatus(412)
            ->assertJson(['message' => MessageHelper::UNAVAILABLE_BALANCE]);
    }
}
