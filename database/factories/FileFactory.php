<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type' => collect(['jpg','jpeg','mp4'])->random(),
            'size' => collect(range(1000,2000))->random(),
            'last_modified' => now()->subdays(9),
            'url' => $this->faker->imageUrl(200, 200, 'cats'),
            'name' => Str::random(10),
            'pinned' => false,
        ];
    }

}
