<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DropdownCart extends Component
{
    //escuchar a ese evento por si se emite desde addcartitem
    public $listeners = ['render'];
    public function render()
    {
        return view('livewire.dropdown-cart');
    }
}
