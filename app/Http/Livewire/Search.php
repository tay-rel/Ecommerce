<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class Search extends Component
{
    public $search;
    public $open = false;   //define si el div se mantiene cerrada sino se busca nada
    public function updatedSearch($value)
    {
        $value ? $this->open = true : $this->open = false;//recibe el valor por el cual ha cambiado
    }
    public function render()
    {
        //recuperamos los productos que el usuario a escrito, filtra por nombre que coincide con el producto
        //si tenemos almacenado algo en la propiedad search que retorne algo
        $products = $this->search
            ? Product::where('name', 'LIKE', "%{$this->search}%")->where('status', 2)->take(8)->get()
            : [];
        return view('livewire.search', compact('products'));
    }
}
