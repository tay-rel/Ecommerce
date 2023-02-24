<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\ShoppingCart;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Faker\Factory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;
use function Sodium\add;

class CartTest extends TestCase
{
    use RefreshDatabase;

//    public function test_example()
//    {
//        // GIVEN Un carrito con productos
//        $product1 = Product::factory()->create();
//        $product2 = Product::factory()->create();
//
//        $carrito = Cart::instance('test-cart-shopping');
//        $carrito->add([
//            'id' => $product1->id,
//            'name' => $product1->name,
//            'qty' => 3,
//            'price' => $product1->price,
//            'weight' => 550,
//            'options' => []
//        ]);
//
//        $carrito->add([
//            'id' => $product2->id,
//            'name' => $product2->name,
//            'qty' => 5,
//            'price' => $product2->price,
//            'weight' => 550,
//            'options' => []
//        ]);
//
//        $carrito->content();
//
//        // WHEN cuando proceso el pedido
//        // THEN el carrito se vacio y se envia un mail
//        $response = $this->get('/');
//
//        $response->assertStatus(200);
//    }

    /******11-semana tres******/
    public function test_add_cart_and_save_logout()
    {
        //logueo
        $user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();//  $this->actingAs($user);

        //Crea un producto
        $category = Category::factory()->create([
            'name'=>'Menu'
        ]);

        $subcategory1 =  Subcategory::factory()->create([
            'category_id'=>$category->id,
            'name'=> 'Submenu'
        ]);

        $marca = Brand::factory()->create();
        $marca->categories()->attach($category->id);

        $product =  Product::factory()->create([
            'name'=>Str::random(5),
            'subcategory_id'=>$subcategory1->id
        ]);

        //solo es un producto creado
        Image::factory()->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        //Añado un carrito, sincolor , ni talla
        Livewire::test(AddCartItem::class, ['product'=>$product])
            ->call('addItem');//call llama solo al metodo del componente

        //El usuario ha echo logout
        // $this->post('/logout');
        //clase que autentica el logout (es una clase de laravel)
        Auth::logout();

         //contar registros que hay en la tabla
         $this->assertDatabaseCount('shoppingcart',1);//encuentra un registro

        //loguin
       $this->actingAs($user);//metodo de phpunit, que recoge un usuario y lo loguea

        Livewire::test(ShoppingCart::class)
            ->assertSee($product->name)
            ->assertSee($product->price)
            ->assertStatus(200);


        /*Antes de añadir y hacer logout no hay nada en el carro y cuando se hace logout e guarda el registro.
        Se guarda cada fila por cada carrito, uno por cada usuarios*/

    }
}
