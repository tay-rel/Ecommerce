<?php

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class UpdateCartItem extends Component
{
    public $rowId;
    public $qty, $quantity;
    public function mount()
    {
        //en item quiero recuperar el producto
        $item = Cart::get($this->rowId);
        $this->qty = $item->qty;
        $this->quantity = qty_available($item->id) + $this->qty;    //almacenamos la informacion del stock ( (15 - qty = 5) + 5 )
    }
    public function decrement()
    {
        $this->qty--;
        //actualiza uno de sus items
        Cart::update($this->rowId, $this->qty);
        //el componente lo escucha dropdown
        $this->emit('render');
    }
    public function increment()
    {
        $this->qty++;
        Cart::update($this->rowId, $this->qty);
        $this->emit('render');
    }
    public function render()
    {
        return view('livewire.update-cart-item');
    }
}
