<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ThreeWeekTest extends TestCase
{
    use RefreshDatabase;
    public function test_product_without_color_can_add_to_shopping_cart()
    {
        $categoria = Category::factory()->create([
            'name'=>'Celulares y tablets'
        ]);

        $subcategoria1 =  Subcategory::factory()->create([
            'category_id'=>$categoria->id,
            'name'=> 'Smartwatches'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($categoria->id);

        //productos
        $producto1 =  Product::factory()->create([
            'subcategory_id' => $subcategoria1->id,
            'name'=>Str::random(5),
            'brand_id' => $marca->id,
            'price'=>19.99
        ]);
        Image::factory()->create([
            'imageable_id' => $producto1->id,
            'imageable_type' => Product::class
        ]);

        $producto2 =  Product::factory()->create([
            'subcategory_id' => $subcategoria1->id,
            'name'=>Str::random(5),
            'brand_id' => $marca->id,
            'price'=>18.99
        ]);
        Image::factory()->create([
            'imageable_id' => $producto2->id,
            'imageable_type' => Product::class
        ]);

        //color


        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
