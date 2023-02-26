<?php

namespace Tests\Browser;

use App\Http\Livewire\AddCartItem;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Department;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Livewire\Livewire;
use Tests\DuskTestCase;

class ShoppingCartTest extends DuskTestCase
{
  /**Semanaa 3 -14***/
    public function test_can_see_all_departments()
    {
            $this->browse(function (Browser $browser) {

            //Dependencias para crear producto
            $category = Category::factory()->create();
            $subcategory = Subcategory::factory()->create([
                'category_id' => $category->id,
            ]);

            $brand = Brand::factory()->create();
            $category->brands()->attach($brand->id);

            //Creo producto
            $product = Product::factory()->create([
                'subcategory_id' => $subcategory->id,
                'brand_id' => $brand->id,
            ]);

                Image::factory()->create([
                    'imageable_id' => $product->id,
                    'imageable_type' => Product::class
                ]);

                Livewire::test(AddCartItem::class, ['product' => $product])
                    ->call('addItem', $product);

                $browser->actingAs(User::factory()->create());
                $departments = Department::factory()->create();

                $browser->visit('/orders/create')
                        ->assertSee($departments->name)
                        ->screenshot('depatamento');
        });
    }
}
