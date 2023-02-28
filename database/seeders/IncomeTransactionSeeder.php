<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IncomeTransaction;

class IncomeTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IncomeTransaction::factory()
                            ->count(30)
                            ->create();
    }
}
