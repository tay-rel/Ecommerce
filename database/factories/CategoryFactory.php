<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'image' => 'categories/' . $this->faker->picsum(storage_path('app/public/categories'), 640, 480, null, false),
            'name' => $this->faker->sentence(),
            'slug' => Str::slug( $this->faker->sentence()),
            'icon' => '<i class="fas fa-mobile-alt"></i>',
        ];
    }
}
