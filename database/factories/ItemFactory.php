<?php

namespace Database\Factories;
 
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Brand;
use App\Models\UnitOfMeasurement;
use App\Models\Item;
 
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $uniquePartNumber = function() {
            do {
                $value = $this->faker->numberBetween(1000000, 9999999);
            } while (Item::where('part_number', $value)->count());
        
            return $value;
        };

        return [
            'category_id' => Category::inRandomOrder()->first()->id,
            'brand_id' => Brand::inRandomOrder()->first()->id,
            'unit_of_measurement_id' => UnitOfMeasurement::inRandomOrder()->first()->id,
            'part_number' => $uniquePartNumber,
            'description' => $this->faker->words(2, true),
            'price' => $this->faker->numberBetween(10000, 1000000)
        ];
    }
}