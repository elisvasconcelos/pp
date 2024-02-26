<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('user_types')->insert([
            'name' => 'Cliente',
        ]);
        DB::table('user_types')->insert([
            'name' => 'Lojista',
        ]);

        User::factory()->create([
            'type_id' => 1,
            'name' => 'Maria José da Silva',
            'document' => '45604117005',
            'email' => 'mariajose@gmail.com',
            'balance' => 1500,
        ]);
        User::factory()->create([
            'type_id' => 1,
            'name' => 'José Maria da Silva',
            'document' => '21058449010',
            'email' => 'josemaria@gmail.com',
            'balance' => 500,
        ]);
        User::factory()->create([
            'type_id' => 1,
            'name' => 'Maria Antonia da Silva',
            'document' => '58913179075',
            'email' => 'maria@gmail.com',
            'balance' => 15,
        ]);
        User::factory()->create([
            'type_id' => 2,
            'name' => 'Mercearia do João',
            'document' => '56752461000175',
            'email' => 'merceariadojoao@gmail.com',
            'balance' => 5000,
        ]);

        DB::table('order_status')->insert([
            'name' => 'pending',
        ]);
        DB::table('order_status')->insert([
            'name' => 'canceled',
        ]);
        DB::table('order_status')->insert([
            'name' => 'unauthorized',
        ]);
        DB::table('order_status')->insert([
            'name' => 'complete',
        ]);

        DB::table('message_status')->insert([
            'name' => 'pending',
        ]);
        DB::table('message_status')->insert([
            'name' => 'sent',
        ]);
        DB::table('message_status')->insert([
            'name' => 'fail',
        ]);
    }
}
