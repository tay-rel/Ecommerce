<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\ShoppingCart;
use App\Listeners\MergeTheCart;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use Faker\Factory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\CreateData;
use Tests\TestCase;
use function Sodium\add;

class CartTest extends TestCase
{
    use RefreshDatabase, CreateData;
    /******11-semana tres******/
    public function test_add_cart_and_save_logout()
    {
        //logueo
        $user = $this->createUser();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();//  $this->actingAs($user);

        //Crea un producto

        $product =  $this->createProduct();

        //Añado un carrito, sincolor , ni talla
        Livewire::test(AddCartItem::class, ['product'=>$product])
            ->call('addItem');//call llama solo al metodo del componente
        $db =Cart::content();//itera sobre el contenido del carrito
        //El usuario ha echo logout
        // $this->post('/logout');
        //clase que autentica el logout (es una clase de laravel)
        Auth::logout();

         //contar registros que hay en la tabla
        $this->assertDatabaseHas('shoppingcart', ['content' => serialize($db)]);//encuentra un registro

        //loguin
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $listener = new MergeTheCart; //Creamos el listener
        $event = new Login('web', $user, true); //Creamos el evento para que el listener lo pueda recibir
        $this->actingAs($user);//metodo de phpunit, que recoge un usuario y lo loguea
        $listener->handle($event); //Se ejecuta el evento

        $db =Cart::content();//itera sobre el contenido del carrito
        Auth::logout();

        $this->assertDatabaseHas('shoppingcart', ['content' => serialize($db)]);

        /*Antes de añadir y hacer logout no hay nada en el carro y cuando se hace logout e guarda el registro.
        Se guarda cada fila por cada carrito, uno por cada usuarios*/

    }
}
