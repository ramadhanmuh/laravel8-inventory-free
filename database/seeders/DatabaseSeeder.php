<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
        $this->call([
            UserSeeder::class,
            ApplicationSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            UnitOfMeasurementSeeder::class,
            ItemSeeder::class,
            IncomeTransactionSeeder::class,
            IncomeTransactionItemSeeder::class,
            ExpenditureTransactionSeeder::class,
            ExpenditureTransactionItemSeeder::class
        ]);
    }
}
