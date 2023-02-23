<?php

namespace Tests\Feature;

use App\Http\Livewire\Navigation;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function test_can_see_menu_category()
    {
        $category = Category::factory()->create([
            'name'=>'Menu'
        ]);

        $response = $this->get('/');
        Livewire::test(Navigation::class)
        ->assertSee('Menu');

        $response->assertStatus(200);
    }
    public function test_can_see_menu_subcategory()
    {
        $category = Category::factory()->create([
            'name'=>'Menu'
        ]);

        $subcategory =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Submenu'
        ]);
        $response = $this->get('/');
        Livewire::test(Navigation::class)
            ->assertSee('Menu')
            ->assertSee('Submenu');;

        $response->assertStatus(200);
    }
}
