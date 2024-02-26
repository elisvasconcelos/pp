<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function ($table) {
            $table->unsignedBigInteger('type_id')->after('id');
            $table->string('document')->after('name')->unique();
            $table->float('balance')->after('password');

            $table->foreign('type_id')->references('id')->on('user_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function ($table) {
            $table->dropForeign(['type_id']);
            $table->dropColumn('document');
            $table->dropColumn('balance');
        });
    }
};
