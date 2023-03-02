<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_name' => Str::random(5),
            'product_line' => Str::random(5),
            'quantity' => $this->faker->numberBetween($min = 0, $max = 100),
            'cost' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99999999.99),
            'quantity_sold' => $this->faker->numberBetween($min = 0, $max = 100)
        ];
    }
}
