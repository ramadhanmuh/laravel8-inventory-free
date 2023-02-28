<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IncomeTransactionItem;

class IncomeTransactionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IncomeTransactionItem::factory()
                            ->count(200)
                            ->create();
    }
}
