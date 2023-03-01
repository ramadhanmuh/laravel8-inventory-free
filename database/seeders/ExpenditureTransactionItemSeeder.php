<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenditureTransactionItem;

class ExpenditureTransactionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenditureTransactionItem::factory()
                                    ->count(200)
                                    ->create();
    }
}
