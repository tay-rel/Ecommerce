<?php

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AddCartItem extends Component
{
    public $product;
    public $quantity;
    public $qty = 1;
    //los productos que estan enviando se debe asegurar que recibe la informacións
    public $options = [
        'color_id' => null,
        'size_id' => null,
    ];


    //cantidad que es respectiva al stock
    public function mount()
    {
        //calcula el stock menos la cantidad agregada
        $this->quantity = qty_available($this->product->id);
        $this->options['image'] = Storage::url($this->product->images->first()->url);
    }
    public function decrement()
    {
        $this->qty--;
    }
    public function increment()
    {
        $this->qty++;
    }
    public function addItem()
    {
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => $this->qty,
            'price' => $this->product->price,
            'weight' => 550,
            'options' => $this->options,
        ]);
        $this->quantity = qty_available($this->product->id);
        //una vez que pinchemos en el botón ‘agregar al carrito de compras’, la cantidad
        //seleccionable del producto regrese al valor de 1
        $this->reset('qty');

        //Queremos que cuando se añada un ítem
        //al carrito de compras desde add-cart-item, la clase AddCartItem le comunique a la otra clase DropdownCart
        //que se ha producido este hecho, y así el numerito del carrito aumente sin tener que actualizar la página
        $this->emitTo('dropdown-cart', 'render');//cuyo primer parámetro es a que vista queremos llamar y como se llama el evento
    }
    public function render()
    {
        return view('livewire.add-cart-item');
    }
}
