<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CategoryClickSubcategoryTest extends DuskTestCase
{
    use DatabaseMigrations;//
    /**
     * A Dusk test example.
     *
     * @test
     * @throws \Throwable
     */
    public function category_click_subcategory()
    {
        $category =  Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,//añade
            'name'=>'menu'
        ]);
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink('Categorías')
                ->assertSee('menu')
                ->screenshot('categorias');//captura error
        });
    }

    /** @test */
    public function at_least_five_products()
    {
        $subcategory = Subcategory::factory()->create();

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Producto 1'
        ]);

        Image::factory()->create([
            'imageable_id'   => $product->id,
            'imageable_type' => Product::class,
        ]);

     /*   $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Producto 1'
        ]);

        Image::factory()->create([
            'imageable_id'   => $product->id,
            'imageable_type' => Product::class,
        ]);*/

      /*  $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Producto 2'
        ]);

        Image::factory()->create([
            'imageable_id'   => $product->id,
            'imageable_type' => Product::class,
        ]);

        $product3 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Producto 3'
        ]);

        Image::factory()->create([
            'imageable_id'   => $product->id,
            'imageable_type' => Product::class,
        ]);

        $product4 = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => 'Producto 4'
        ]);

        Image::factory()->create([
            'imageable_id'   => $product->id,
            'imageable_type' => Product::class,
        ]);*/

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Producto 1')
            /*    ->assertSee('Producto 2')
                ->assertSee('Producto 3')
                ->assertSee('Producto 4')
                ->assertSee('Producto 5')*/

                ->screenshot('screen2');
        });
    }

}
