<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\IncomeTransaction;
use App\Models\IncomeTransactionItem;
use App\Models\Item;

class IncomeTransactionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $input = collect([]);

        for ($i=0; $i < 250; $i++) { 
            $data['income_transaction_id'] = IncomeTransaction::inRandomOrder()->first()->id;

            $data['item_id'] = Item::inRandomOrder()->first()->id;

            if ($input->count()) {
                while ($input->where('income_transaction_id', $data['income_transaction_id'])
                                        ->where('item_id', $data['item_id'])
                                        ->count())
                {
                    $data['item_id'] = Item::inRandomOrder()->first()->id;
                }
            }

            $data['amount'] = rand(1000, 1000000000);

            $input->push($data);
        }

        IncomeTransactionItem::insert($input->toArray());
    }
}