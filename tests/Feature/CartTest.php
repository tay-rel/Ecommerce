<?php

namespace Tests\Feature;

use App\Models\Product;
use Faker\Factory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Tests\TestCase;
use function Sodium\add;

class CartTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
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
    }
}
