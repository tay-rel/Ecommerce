<?php

namespace Tests\Browser;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    use RefreshDatabase;


    /**@test*/
    public function testBasicExample()
    {
        $category = Category::factory()->create();//

        $this->browse(function (Browser $browser) {
            $browser->visit('/')///login
            ->assertSee('Categorías')//Correo electronico
            ->screenshot('prueba');//captura error
        });
    }
}
