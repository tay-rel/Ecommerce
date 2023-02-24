<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Http\Livewire\CreateOrder;
use App\Http\Livewire\DropdownCart;
use App\Http\Livewire\ShoppingCart;
use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Color;
use App\Models\Department;
use App\Models\District;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ThreeWeekTest extends TestCase
{
    use RefreshDatabase;

    /*****************1******************/
    public function test_three_product_can_add_to_shopping_cart_whithout_color()
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

        Livewire::test(AddCartItem::class,['product'=>$producto1] )
        ->call('addItem');

        Livewire::test(ShoppingCart::class)
            ->assertSee($producto1->name);
    }
    public function test_three_product_can_add_to_shopping_cart_whit_color()
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

        $color = Color::factory()->create([
            'name'=>'Azul',
        ]);
        $producto1->colors()->attach([//la relacion llama a la tabla pivote
            $color->id=>['quantity'=>5]
        ]);

        Livewire::test(AddCartItemColor::class,['product'=>$producto1] )
            ->set('color_id', $color->id)
            ->call('addItem');

        Livewire::test(ShoppingCart::class)
            ->assertSee($producto1->name)
            ->assertDontSee($producto2->name);
    }
    public function test_three_product_can_add_to_shopping_cart_whit_color_and_size()
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
        $color = Color::factory()->create([
            'name'=>'Azul',
        ]);

        $producto1->sizes()->create([
            'name'=>'Talla S'
        ]);

        //crea talla, que esta relacionado con el producto
        $talla = Size::first();

        //relacion color, porque el color esta relacionado con la talla
        $talla->colors()->attach([
            $color->id =>['quantity'=>5],
        ]);

        Livewire::test(AddCartItemSize::class,['product'=>$producto1] )
            ->set('size_id', $producto1->sizes[0]->id)
            ->set('color_id', $color->id)
            ->call('addItem');

        Livewire::test(ShoppingCart::class)
            ->assertSee($producto1->name)
        ->assertDontSee($producto2->name);
    }

    /*****************2******************/
    public function test_can_see_icon_shopping_cart()
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

        Livewire::test(AddCartItem::class, ['product'=>$producto1])
            ->call('addItem');

        Livewire::test(DropdownCart::class)
        ->assertSee($producto1->name)
        ->assertDontSee($producto2->name);
    }

    /*****************3******************/
    public function test_can_see_add_shopping_cart_and_see_circle_red()
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

        Livewire::test(AddCartItem::class, ['product'=>$producto1])
            ->call('addItem');

        Livewire::test(DropdownCart::class)
            ->assertSee($producto1->name)
             ->assertSee(1);
    }

    /*****************5******************/
    public function test_check_stock_this_disponible()
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

        $this->get('products/'. $producto1->slug)
        ->assertSee($producto1->quantity);

    }
    /******13******/

    public function test_check_create_product_and_destroy_and_newRoute()
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

        $this->actingAs(User::factory()->create());

        //añade al carrito
        Livewire::test(AddCartItem::class,['product'=>$producto1])
        ->call('addItem');


        //crea la orden
        Livewire::test(CreateOrder::class)
        ->set('contact', 'Carlos')
            ->set('phone', '8798654687')
        ->call('create_order')
        ->assertRedirect('/orders/1/payment')
        ->assertSee($producto1->name);


        //carrito esta vacio, cuando pagas
        $this->assertEquals(0, Cart::count());

        //en la bbdd order, este guardado el carrito, donde hay orden en cada fila
        $this->assertDatabaseCount('orders',1);

    }
    /******14******/
    public function test_check_select_load_option_last()
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

        $departamento1 =Department::factory()->create();
        $departamento2 =Department::factory()->create();

        $ciudad1=City::factory()->create([
            'name'=>'ciudad1',
            'department_id'=>$departamento1->id
        ]);
        $ciudad2=City::factory()->create([
            'name'=>'ciudad2',
            'department_id'=>$departamento2->id
        ]);

        $distrito1=District::factory()->create([
            'city_id'=>$ciudad1->id
        ]);
        $distrito2=District::factory()->create([
            'city_id'=>$ciudad2->id
        ]);

        //logueo
        $this->actingAs(User::factory()->create());

        //añade al carrito
        Livewire::test(AddCartItem::class,['product'=>$producto1])
            ->call('addItem');

        //crea la orden
        Livewire::test(CreateOrder::class)
            ->set('envio_type', 2)//creo tipo de envio
            ->assertSee($departamento1->name)
            ->assertSee($departamento2->name)
             ->set('department_id', $departamento1->id)
            ->assertSee($ciudad1->name)
            ->assertDontSee($ciudad2->name)
            ->set('city_id', $ciudad1->id)
            ->assertSee($distrito1->name)
            ->assertDontSee($distrito2->name);



    }
}
