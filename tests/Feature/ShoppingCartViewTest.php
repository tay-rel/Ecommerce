<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Http\Livewire\ShoppingCart;
use App\Http\Livewire\UpdateCartItem;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\CreateData;
use Tests\TestCase;

class ShoppingCartViewTest extends TestCase
{
    use RefreshDatabase, CreateData;

    //ST3:7 Al acceder a la vista del carrito, comprobar que podemos ver todos los items que tenga.
    public function test_watch_show_shoppingcart_of_items()
    {
        $product = $this->createProduct();//ni color ni talla
        $product1 = $this->createProduct();//ni color ni talla
        $product2 = $this->createProduct(true);//producto con color
        $product3 = $this->createProduct(true, true);//producto con color y talla

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->call('addItem', $product1);

        Livewire::test(AddCartItemColor::class, ['product' => $product2])
            ->call('addItem', $product2);

        Livewire::test(AddCartItemSize::class, ['product' => $product3])
            ->call('addItem', $product);

        $response = $this->get('/shopping-cart');
        $response->assertStatus(200)
            ->assertDontSee($product->name)
            ->assertSee($product1->name)
            ->assertSee($product2->name)
            ->assertSee($product3->name);
    }

    //ST3:8-Comprobar que en dicha vista podemos cambiar la cantidad a cualquiera de ellos.
    // Y la columna Total cambia consecuentemente.
    public function test_checkout_that_view_shoppingcart_can_change_quantity()
    {
        $product = $this->createProduct();//ni color ni talla

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $total = Cart::subtotal();// para obtener el subtotal actual del carrito de compras.

        Livewire::test(UpdateCartItem::class,
            ['rowId' =>Cart::content()->first()->rowId])
            ->call('increment');
        $this->assertEquals($total *2 ,Cart::subtotal());//verificar que el subtotal del carrito de compras se ha actualizado correctamente y se ha duplicado.

        Livewire::test(UpdateCartItem::class, ['rowId' => Cart::content()->first()->rowId])
            ->call('decrement');
        $this->assertEquals($total, Cart::subtotal());

    }
    //ST3-9 Comprobar que podemos vaciar el carrito.
    public function test_checkout_that_view_shoppingcart_can_be_empty()
    {
        $product = $this->createProduct();//ni color ni talla
        $product2 = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(AddCartItem::class, ['product' => $product2])
            ->call('addItem', $product2);

        Livewire::test(ShoppingCart::class)
            ->call('destroy', Cart::content()->first()->rowId);

        $this->assertTrue(count(Cart::content()) == 0);
    }

    //TS3:  Y también que se puede borrar un producto.
    public function test_delete_a_product()
    {
        $product = $this->createProduct();
        $product2 = $this->createProduct();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(AddCartItem::class, ['product' => $product2])
            ->call('addItem', $product2);

        Livewire::test(ShoppingCart::class)
            ->call('delete', Cart::content()->first()->rowId);

        $this->assertTrue(count(Cart::content()) == 1);
    }
}
