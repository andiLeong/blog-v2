<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => collect(['delivered','shipped','unshipped','received'])->random(),
            'price' => rand(10,9999),
            'paid' => collect([0,1])->random(),
            'country' => collect(['American','Malaysia','India','China','Singapore','Russia','United Kingdom','Canada','Brazil'])->random(),
            'customer' => $this->faker->name(),
            'number' => Str::random(10),
        ];
    }
}
