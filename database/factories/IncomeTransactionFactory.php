<?php

namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\IncomeTransaction;
 
class IncomeTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $uniqueReferenceNumber = function() {
            do {
                $value = $this->faker->bothify('?????-#####');
            } while (IncomeTransaction::where('reference_number', $value)->count());
        
            return $value;
        };

        return [
            'supplier' => $this->faker->name(),
            'reference_number' => $uniqueReferenceNumber,
            'remarks' => $this->faker->realText(100),
            'created_at' => $this->faker->unixTime()
        ];
    }
}