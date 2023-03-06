<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class ShowProducts2 extends Component
{
    public $search;

    public function render()
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")->paginate(10);
        return view('livewire.admin.show-products2', compact('products')) ->layout('layouts.admin');
    }
}
