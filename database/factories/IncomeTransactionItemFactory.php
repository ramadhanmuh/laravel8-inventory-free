<?php

namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IncomeTransaction;
use App\Models\IncomeTransactionItem;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
 
class IncomeTransactionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // return [
        //     'income_transaction_id' => $income_transaction_id,
        //     'item_id' => $unique_item_id,
        //     'amount' => $this->faker->numberBetween(1, 9999999999)
        // ];
    }
}