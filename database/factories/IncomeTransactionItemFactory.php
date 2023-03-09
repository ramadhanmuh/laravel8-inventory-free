<?php

namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IncomeTransaction;
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
        return [
            'income_transaction_id' => IncomeTransaction::inRandomOrder()->first()->id,
            'item_id' => Item::inRandomOrder()->first()->id,
            'amount' => $this->faker->numberBetween(1, 9999999999)
        ];
    }
}