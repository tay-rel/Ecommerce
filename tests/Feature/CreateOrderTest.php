<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\CreateOrder;
use App\Listeners\MergeTheCart;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\CreateData;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase, CreateData;

    //TS3: 10 -Comprobar que solo un usuario autenticado puede entrar a crear un pedido.
    public function test_a_not_auth_user_cannot_create_an_order()
    {
        $response = $this->get('/orders/create');
        $response->assertRedirect('/login');
    }
    public function test_a_auth_user_can_create_an_order()
    {
        $user =$this->actingAs($this->createUser());
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' =>$product])->call('addItem' , $product);
        $user->get('/orders/create')->assertStatus(200);


        Livewire::test(CreateOrder::class)
            ->assertSee(Cart::content()->first()->name);//comprueba que el contenido de la página incluya un texto específico.
    }

    //TS3:11. Comprobar que el carrito se guarda en la BD cuando se cierra sesión
    public function test_checkout_shopping_cart_is_saved_in_db_when_is_logout()
    {
        $user =$this->actingAs($this->createUser());
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' =>$product])
            ->call('addItem' , $product);

        $db =Cart::content();//itera sobre el contenido del carrito

        $this->post('/logout');

        $this->assertDatabaseHas('shoppingcart', ['content' => serialize($db)]);
    }

    //TS3:11 y se recupera en caso de iniciar sesión y exista.
    public function test_checkout_shopping_cart_is_saved_in_when_user_is_login()
    {
        $user =$this->actingAs($this->createUser());
        $product = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' =>$product])
            ->call('addItem' , $product);

        $db =Cart::content();//itera sobre el contenido del carrito
        $this->post('/logout');

        $listener = new MergeTheCart();   //debe verificar si tenemos un registro cuando iniciamos sesion
        $event = new Login('web', $this->createUser(), true);

        $listener->handle($event);
        $this->assertDatabaseHas('shoppingcart', ['content' => serialize($db)]);
    }


    //TS3:13  Comprobar que se crea el pedido y se destruye el carrito. Y se redirige a la nueva ruta.
    public function test_checkout_shopping_cart_is_destroy_when_the_order_is_created()
    {
        $user =$this->actingAs($this->createUser());
        $product = $this->createProduct();
        //añade al carrito
        Livewire::test(AddCartItem::class, ['product' =>$product])
            ->call('addItem' , $product);

        Livewire::test(CreateOrder::class)
        ->set('contact','Pepe' )
        ->set('phone','123456')
        ->call('create_order')
        ->assertRedirect('orders/1/payment');

        $this->assertTrue(count(Cart::content()) == 0);
    }
}
