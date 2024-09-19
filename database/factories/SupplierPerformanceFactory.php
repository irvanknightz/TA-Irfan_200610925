<?php

namespace Database\Factories;

use App\Models\SupplierPerformance;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierPerformanceFactory extends Factory
{
    protected $model = SupplierPerformance::class;

    /**
     * Define the supplier performance's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'supplier_id' => Supplier::factory(),
            'date' => $this->faker->date(),
            'year' => $this->faker->numberBetween(1900, 2100),
            'product_defect' => $this->faker->numberBetween(0, 100),
            'delivery' => $this->faker->numberBetween(0, 100),
            'cost' => $this->faker->numberBetween(0, 100),
            'return_time' => $this->faker->numberBetween(0, 100),
        ];
    }
}
