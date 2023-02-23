<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\User;
use Database\Factories\CategoryFactory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome;
use Tests\DuskTestCase;

class CartTest extends DuskTestCase
{
    use DatabaseMigrations;//corrre las migraciones de bbdd antes de cada test
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
//        Category::factory()->create();
//        //productos
//
//
//        $usuario = User::factory()->create();
//
//        $this->browse(function (Browser $browser) use ($usuario) {
//            $browser->visit('/login')
//                    ->type('email', $usuario->email)
//                    ->type('password',  'password')
//                    ->press('INICIAR SESIÓN')
//                    ->assertPathIs('/')
//                    ->screenshot('carrito');//captura error;
//        });
    }
}
