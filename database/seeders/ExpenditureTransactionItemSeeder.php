<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenditureTransactionItem;
use App\Models\Item;
use App\Models\ExpenditureTransaction;

class ExpenditureTransactionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $input = collect([]);

        for ($i=0; $i < 100; $i++) { 
            $data['expenditure_transaction_id'] = ExpenditureTransaction::inRandomOrder()->first()->id;

            $data['item_id'] = Item::inRandomOrder()->first()->id;

            if ($input->count()) {
                while ($input->where('expenditure_transaction_id', $data['expenditure_transaction_id'])
                                        ->where('item_id', $data['item_id'])
                                        ->count())
                {
                    $data['item_id'] = Item::inRandomOrder()->first()->id;
                }
            }

            $data['amount'] = rand(1, 10000000);

            $input->push($data);
        }

        ExpenditureTransactionItem::insert($input->toArray());
    }
}
