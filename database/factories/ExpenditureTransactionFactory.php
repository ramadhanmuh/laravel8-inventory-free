<?php

namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ExpenditureTransaction;
 
class ExpenditureTransactionFactory extends Factory
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
            } while (ExpenditureTransaction::where('reference_number', $value)->count());
        
            return $value;
        };

        return [
            'picker' => $this->faker->name(),
            'reference_number' => $uniqueReferenceNumber,
            'remarks' => $this->faker->realText(100),
            'created_at' => $this->faker->unixTime()
        ];
    }
}