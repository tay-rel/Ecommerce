<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
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

        /* Cada producto está en una subcategoría y tiene una marca.
         Pero la marca está en una categoría de debe de tener como una
        de sus subcategorías a la del product*/
        $name = $this->faker->sentence(2);
<<<<<<< HEAD
        $subcategory = Subcategory::all()->random();            // Subcategory::all()->random();
=======
        $subcategory =   Subcategory::all()->random()  ;             // Subcategory::all()->random();
>>>>>>> f6c535d827244f2258f1e2349219b5e5aaec6ec6
        $category = $subcategory->category;
        $brand = $category->brands->random();
        //$brand =Brand::factory()->create();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomElement([19.99, 49.99, 99.99]),
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'quantity'=> $subcategory->color ? null : 15,
            'status' => 2
        ];
    }
}
