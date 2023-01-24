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
    public $options = [];

    //cantidad que es respectiva al stock
    public function mount()
    {
        $this->quantity = $this->product->quantity;
        //Cuando añadamos un elemento al carrito,
        //también enviaremos una imagen.
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
    }
    public function render()
    {
        return view('livewire.add-cart-item');
    }
}
