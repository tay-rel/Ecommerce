<?php

namespace Tests\Browser;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Database\Factories\CategoryFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Tests\DuskTestCase;

class TwoWeekDuskTest extends DuskTestCase
{
    use DatabaseMigrations;//corrre las migraciones de bbdd antes de cada test

    public function test_it_displays_name_category()
    {
        $categories = Category::factory(2)->create();
        $category1 = $categories[0];
        $category2 = $categories[1];

        $this->browse(function (Browser $browser) use ($category1, $category2) {
            $browser->visit('/categories/' . $category1->slug)
                ->assertSee(strtoupper($category1->name))
                ->assertDontSee(strtoupper($category2->name))
                ->screenshot('categories');
        });
    }


    public function test_it_displays_its_own_products()
    {
        $categories = Category::factory(2)->create();
        $category1 = $categories[0];
        $category2 = $categories[1];
        $subcategory1 = Subcategory::factory()->create([
            'category_id' => $category1->id,
        ]);
        $subcategory2 = Subcategory::factory()->create([
            'category_id' => $category2->id,
        ]);
        $brand = Brand::factory()->create();
        $category1->brands()->attach([$brand->id]);
        $category2->brands()->attach([$brand->id]);

        $product1 = Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
            'brand_id' => $brand->id,
        ]);
        $product2 = Product::factory()->create([
            'subcategory_id' => $subcategory2->id,
            'brand_id' => $brand->id,
        ]);

        Image::factory(2)->create([
            'imageable_id' => $product1->id,
            'imageable_type' => Product::class
        ]);

        Image::factory(2)->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);

        $this->browse(function (Browser $browser) use ($category1,$product1, $product2 ) {
            $browser->visit('/categories/' . $category1->slug)
                ->assertSeeLink($product1->name)
                ->assertDontSeeLink($product2->name);
        });
    }
    /**************5***************/
    public function test_can_see_menu_with_its_subcategories_and_brands()
    {
        $categories = Category::factory(2)->create();
        $category1 = $categories[0];
        $category2 = $categories[1];

        $subcategory1 = Subcategory::factory()->create([
            'category_id' => $category1->id,
        ]);
        $subcategory2 = Subcategory::factory()->create([
            'category_id' => $category2->id,
        ]);

        $brands = Brand::factory(2)->create();
        $category1->brands()->attach([$brands[0]->id]);
        $category2->brands()->attach([$brands[1]->id]);

        $this->browse(function (Browser $browser) use ($category1, $subcategory1, $subcategory2, $brands) {
            $browser->visit('/categories/' . $category1->slug)
                ->assertSeeLink($subcategory1->name)
                ->assertDontSeeLink($subcategory2->name)
                ->assertSeeLink($brands[0]->name)
                ->assertDontSeeLink($brands[1]->name)
                ->screenshot('filtros');
        });
    }
}
