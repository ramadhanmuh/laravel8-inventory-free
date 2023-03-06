<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'John Doe',
                'email' => 'mrama08282@gmail.com',
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'role' => 'Admin',
                'created_at' => time()
            ],
            [
                'name' => 'Jane Doe',
                'email' => 'janedoe@gmail.com',
                'username' => 'operator',
                'password' => Hash::make('operator'),
                'role' => 'Operator',
                'created_at' => time()
            ]
        ]);

        User::factory()
            ->count(50)
            ->create();
    }
}
