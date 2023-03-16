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

        DB::table('applications')->insert([
            'name' => 'Aplikasi Inventaris',
            'copyright' => 'Inventaris 2023'
        ]);        

        // $this->call([
        //     UserSeeder::class,
        //     ApplicationSeeder::class,
        //     CategorySeeder::class,
        //     BrandSeeder::class,
        //     UnitOfMeasurementSeeder::class,
        //     ItemSeeder::class,
        //     IncomeTransactionSeeder::class,
        //     IncomeTransactionItemSeeder::class,
        //     ExpenditureTransactionSeeder::class,
        //     ExpenditureTransactionItemSeeder::class
        // ]);
    }
}
