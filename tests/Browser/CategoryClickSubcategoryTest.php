<?php

namespace Tests\Browser;

use App\Models\Category;
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
}
