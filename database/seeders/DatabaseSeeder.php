<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'muhrama082@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'role' => 'Admin',
            'created_at' => time()
        ]);
    }
}
