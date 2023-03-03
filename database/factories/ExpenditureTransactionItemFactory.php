<?php

namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ExpenditureTransaction;
use App\Models\ExpenditureTransactionItem;
use App\Models\Item;
 
class ExpenditureTransactionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $expenditure_transaction_id = ExpenditureTransaction::inRandomOrder()->first()->id;

        $unique_item_id = function() use ($expenditure_transaction_id) {
            do {
                $value = ExpenditureTransaction::inRandomOrder()->first()->id;
                $item = ExpenditureTransactionItem::where(
                    'expenditure_transaction_id', $expenditure_transaction_id
                )
                ->where('item_id', $value)
                ->count();
            } while ($item);
        
            return $value;
        };

        return [
            'expenditure_transaction_id' => $expenditure_transaction_id,
            'item_id' => $unique_item_id,
            'amount' => $this->faker->numberBetween(1, 999)
        ];
    }
}