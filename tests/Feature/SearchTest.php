<?php

namespace Tests\Feature;

use App\Http\Livewire\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\CreateData;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase, CreateData;
//TS3:-6.-Comprobar que el buscador es capaz de filtrar según la entrada de datos
    public function test_the_search_filter_by_name()
    {
        $this->createOnlyProduct('Consola');
        $this->createOnlyProduct('TV');

        Livewire::test(Search::class)
            ->set('search', 'Cons')
            ->assertSee('Consola')
            ->assertDontSee('TV');

    }
    //TS3:o mostrar todos si está vacío(que no se haya buscado nada).
    public function test_it_doesnt_show_any_product_if_search_input_is_empty()
    {

        $this->createOnlyProduct('TV');
        $this->get('/');

        Livewire::test(Search::class)
            ->set('search', '')
            ->assertDontSee('TV');

    }

}
