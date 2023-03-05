<?php

namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IncomeTransaction;
use App\Models\IncomeTransactionItem;
use App\Models\Item;
 
class IncomeTransactionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $income_transaction_id = IncomeTransaction::inRandomOrder()->first()->id;

        $unique_item_id = function() use ($income_transaction_id) {
            do {
                $value = IncomeTransaction::inRandomOrder()->first()->id;
                $item = IncomeTransactionItem::where(
                    'income_transaction_id', $income_transaction_id
                )
                ->where('item_id', $value)
                ->first();
            } while (!empty($item));
        
            return $value;
        };

        return [
            'income_transaction_id' => $income_transaction_id,
            'item_id' => $unique_item_id,
            'amount' => $this->faker->numberBetween(1, 9999999999)
        ];
    }
}