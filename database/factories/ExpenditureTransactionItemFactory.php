<?php

namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ExpenditureTransaction;
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
        return [
            'expenditure_transaction_id' => ExpenditureTransaction::inRandomOrder()->first()->id,
            'item_id' => Item::inRandomOrder()->first()->id,
            'amount' => $this->faker->numberBetween(1, 9999999999)
        ];
    }
}