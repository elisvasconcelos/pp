<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->unsignedBigInteger('payer');
            $table->unsignedBigInteger('payee');
            $table->unsignedBigInteger('status_id');
            $table->timestamps();

            $table->foreign('payer')->references('id')->on('users');
            $table->foreign('payee')->references('id')->on('users');
            $table->foreign('status_id')->references('id')->on('order_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
