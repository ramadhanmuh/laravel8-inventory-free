<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenditureTransaction;

class ExpenditureTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ExpenditureTransaction::factory()
                            ->count(25)
                            ->create();
    }
}
