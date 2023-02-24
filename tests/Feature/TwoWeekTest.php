<?php

namespace Tests\Feature;

use App\Http\Livewire\CategoryProducts;
use App\Http\Livewire\Navigation;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Livewire\Livewire;
use PhpParser\Node\Expr\Array_;
use Tests\TestCase;

class TwoWeekTest extends TestCase
{
    use RefreshDatabase;

    /************1***********/
    public function test_check_link()
    {

        $category = Category::factory()->create([
            'name'=>'Menu'
        ]);
        $subcategory =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Submenu'
        ]);

        $response = $this->get('/')
            ->assertSee('Iniciar sesión')
            ->assertSee('Registrarse')
            ->assertDontSee('Perfil');
        $response->assertStatus(200);
    }

    public function test_user_login()
    {
        $category = Category::factory()->create([
            'name'=>'Menu'
        ]);
        $subcategory =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Submenu'
        ]);

        //logueo
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $response =$this->get('/')
        ->assertSee('Perfil')
        ->assertSee('Finalizar sesión')
        ->assertDontSee('Registrarse')
        ->assertDontSee('Iniciar sesión');

        $response->assertStatus(200);
    }

    /************2***********/

    public function test_can_see_five_products()
    {

        $category = Category::factory()->create([
            'name'=>'Menu'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Submenu'
        ]);

       $marca = Brand::factory()->create();
       $marca->categories()->attach($category->id);

      $products =  Product::factory(5)->create([
          'name'=>Str::random(5),
            ])->each(function(Product $product){//5*1
            Image::factory()->create([
                'imageable_id' => $product->id,
                'imageable_type' => Product::class
            ]);
        });

      Livewire::test(CategoryProducts::class, ['category'=>$category])
          ->set('products',$products)
          ->assertSee($products[0]->name)
          ->assertSee($products[1]->name)
          ->assertSee($products[2]->name)
          ->assertSee($products[3]->name)
          ->assertSee($products[4]->name);

        $response =$this->get('/')
            ->assertSee('Menu');
   }

    /************3***********/

    public function test_can_see_five_products_public()
    {

        $category = Category::factory()->create([
            'name'=>'Menu'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Submenu'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $products =  Product::factory(3)->create([
            'name'=>Str::random(5),
        ])->each(function(Product $product){//5*1
            Image::factory()->create([
                'imageable_id' => $product->id,
                'imageable_type' => Product::class
            ]);
        });

        for ( $i=0; $i <2 ; $i++){
            $product= Product::factory()->create([
                'name'=>Str::random(5),
                'status'=>1
            ]);
                Image::factory()->create([
                    'imageable_id' => $product->id,
                    'imageable_type' => Product::class
                ]);
                $products[] = $product;//añade en la ultima posicion de array
        }

        Livewire::test(CategoryProducts::class, ['category'=>$category])
            ->call('loadProducts')
            ->assertSee($products[0]->name)
            ->assertSee($products[1]->name)
            ->assertSee($products[2]->name)
            ->assertDontSee($products[3]->name)
            ->assertDontSee($products[4]->name);

        $response =$this->get('/')
            ->assertSee('Menu');
    }

    /************4***********/
    public function test_check_can_see_details()
    {
        $category = Category::factory()->create([
            'name'=>'Celulares y tablets'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Smartwatches'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $product =  Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
            'name'=>Str::random(5),
            'brand_id' => $marca->id,
            'price'=>19.99
        ]);
            Image::factory()->create([
                'imageable_id' => $product->id,
                'imageable_type' => Product::class
            ]);
        $product2 =  Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
            'name'=>Str::random(5),
            'brand_id' => $marca->id,
            'price'=>18.99
        ]);
        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);

        $response =$this->get('products/' . $product->slug)
            ->assertSee('Celulares y tablets')
            ->assertSee('Smartwatches')
            ->assertSee($product->name)
            ->assertSee($product->price)
            ->assertSeeText('Descripción')
            ->assertDontSee($product2->name)
            ->assertDontSee($product2->price);
    }

    /************5***********/
    public function test_check_vist_detail_filter_subcategory_or_brand()
    {
        $category = Category::factory()->create([
            'name'=>'Celulares y tablets'
        ]);

        $category2 = Category::factory()->create([
            'name'=>'TV, audio y video'
        ]);

        $subcategory =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Smartwatches'
        ]);
        $subcategory2 =  Subcategory::factory()->create([
            'category_id'=>$category2->id,
            'name'=> 'Audios'
        ]);

//        dd($subcategory2);
        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $marca2 = Brand::factory()->create();
        $marca2->categories()->attach($category2->id);

        $product =  Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name'=>Str::random(5),
            'brand_id' => $marca->id,
            'price'=>19.99
        ]);
        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);
        $product2 =  Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name'=>Str::random(5),
            'brand_id' => $marca->id,
            'price'=>18.99
        ]);
        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);
        //categoriescategories/celulares-y-tablets?subcategoria=smartwatches

        $response =$this->get('categories/' . $category->slug)
            ->assertSee('Celulares y tablets')
            ->assertSee($subcategory->name)
            ->assertSee($marca->name);
//            ->assertDontSee($subcategory2->name)
//            ->assertDontSee($marca2->name);
    }

}
