<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Http\Livewire\CategoryFilter;
use App\Http\Livewire\CategoryProducts;
use App\Http\Livewire\Navigation;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
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
    public function test_check_can_see_details_category()
    {
        $category = Category::factory()->create([
            'name'=>'Celulares y tablets'
        ]);
        $category2 = Category::factory()->create([
            'name'=>'Moda'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Smartwatches'
        ]);

        $subcategory2 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Accesorios para celulares'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $marca2 = Brand::factory()->create();
        $marca2->categories()->attach($category->id);

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
            'subcategory_id' => $subcategory2->id,
            'name'=>Str::random(5),
            'brand_id' => $marca2->id,
            'price'=>18.99
        ]);
        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);

        Livewire::test(CategoryFilter::class, ['category'=>$category])
                   ->assertSee($category->name)
                   ->assertSee($subcategory1->name)
                   ->assertSee($subcategory2->name)
                   ->assertSee($marca->name)
                   ->assertSee($marca2->name)
                   ->assertSee($product->name)
                   ->assertSee($product2->name)
                   ->assertDontSee($category2->name);
    }

    /************5***********/
    public function test_check_vist_detail_filter_subcategory()
    {
        $category = Category::factory()->create([
            'name'=>'Celulares y tablets'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Smartwatches'
        ]);
        $subcategory2 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Accesorios para celulares'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $marca2 = Brand::factory()->create();
        $marca2->categories()->attach($category->id);

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
            'subcategory_id' => $subcategory2->id,
            'name'=>Str::random(5),
            'brand_id' => $marca2->id,
            'price'=>18.99
        ]);
        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);

        Livewire::test(CategoryFilter::class, ['category' =>$category])//llega al componente con la variable,a la vista le llega la vaiable
        ->set('subcategoria' , $subcategory1->slug)
        ->assertSee($category->name)
        ->assertSee($product->name)
        ->assertDontSee($product2->name);

    }
    public function test_check_vist_detail_filter_brand()
    {

        $category = Category::factory()->create([
            'name'=>'Celulares y tablets'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Smartwatches'
        ]);
        $subcategory2 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Accesorios para celulares'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $marca2 = Brand::factory()->create();
        $marca2->categories()->attach($category->id);

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
            'subcategory_id' => $subcategory2->id,
            'name'=>Str::random(5),
            'brand_id' => $marca2->id,
            'price'=>18.99
        ]);
        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);

        //la marca depende de la subcategoria
        Livewire::test(CategoryFilter::class, ['category' =>$category])
            ->set('subcategoria' , $subcategory1->slug)
            ->set('marca' , $marca->name)
            ->assertSee($category->name)
            ->assertSee($product->name)
            ->assertSee($marca->name)
            ->assertDontSee($product2->name);

    }
    /************6***********/
    public function test_check_can_see_details_product()
    {
        $category = Category::factory()->create([
            'name'=>'Celulares y tablets'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Smartwatches'
        ]);

        $subcategory2 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Accesorios para celulares'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $marca2 = Brand::factory()->create();
        $marca2->categories()->attach($category->id);

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
            'subcategory_id' => $subcategory2->id,
            'name'=>Str::random(5),
            'brand_id' => $marca2->id,
            'price'=>18.99
        ]);
        Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);

        $this->get('products/' .  $product->slug)
            ->assertSee($product->name)
                ->assertSee($marca->name)
             ->assertSee($product->price)
            ->assertSeeText('Descripción')
        ->assertDontSee($product2->name);
    }

    /************7***********/
    public function test_details_product_whitout_color_image_description_name_price_stock_button_and_add_shopping_cart()
    {
        $category = Category::factory()->create([
            'name'=>'Celulares y tablets'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Smartwatches'
        ]);

        $subcategory2 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Accesorios para celulares'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $marca2 = Brand::factory()->create();
        $marca2->categories()->attach($category->id);

        $product =  Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
            'name'=>Str::random(5),
            'brand_id' => $marca->id,
            'price'=>19.99
        ]);
       $imagen1= Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);
        $product2 =  Product::factory()->create([
            'subcategory_id' => $subcategory2->id,
            'name'=>Str::random(5),
            'brand_id' => $marca2->id,
            'price'=>18.99
        ]);
       $imagen2 = Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);

       //creo carrito
      Livewire::test(AddCartItem::class,['product'=>$product])
      ->call('addItem');//añade carrito


        $this->get('products/' . $product->slug)
            ->assertSee($product->name)
            ->assertSee($marca->name)
            ->assertSee($imagen1->url)
            ->assertSee($product->description)
            ->assertSeeText('Descripción')
            ->assertSee($product->price)
            ->assertSee($product->quantity)
            ->assertSeeText('+')
            ->assertSeeText('-')
            ->assertSeeText('Agregar al carrito de compras')
            ->assertDontSee($product2);
    }

    /************8***********/
    public function test_check_select_color()
    {
        $category = Category::factory()->create([
            'name'=>'Celulares y tablets'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Smartwatches'
        ]);

        $subcategory2 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Accesorios para celulares'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $marca2 = Brand::factory()->create();
        $marca2->categories()->attach($category->id);
        $color = Color::factory()->create([
            'name'=>'Azul',
        ]);

        $product =  Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
            'name'=>Str::random(5),
            'brand_id' => $marca->id,
            'price'=>19.99
        ]);

        $product->colors()->attach([//la relacion llama a la tabla pivote
            $color->id=>['quantity'=>5]
        ]);

        $imagen1= Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);
        $product2 =  Product::factory()->create([
            'subcategory_id' => $subcategory2->id,
            'name'=>Str::random(5),
            'brand_id' => $marca2->id,
            'price'=>18.99
        ]);
        $imagen2 = Image::factory()->create([
            'imageable_id' => $product2->id,
            'imageable_type' => Product::class
        ]);

        Livewire::test(AddCartItemColor::class,['product'=>$product] )
        ->assertSee($color->name);
    }

    public function test_check_select_color_and_size()
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

        $color = Color::create([
            'name'=>'Azul',
        ]);

        $product =  Product::factory()->create([
            'subcategory_id' => $subcategory1->id,
            'name'=>Str::random(5),
            'brand_id' => $marca->id,
            'price'=>19.99
        ]);

        $product->sizes()->create([
            'name'=>'Talla S'
        ]);

        //crea talla, que esta relacionado con el producto
        $talla = Size::first();

        //relacion color, porque el color esta relacionado con la talla
        $talla->colors()->attach([
            $color->id =>['quantity'=>5],
        ]);

        $imagen1= Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);


        Livewire::test(AddCartItemSize::class, ['product'=>$product])
            ->set('size_id',$product->sizes[0]->id)//producto llama a la relacion con talla y su id
            ->assertSee($talla->name)
            ->assertSee($color->name);
    }
}
