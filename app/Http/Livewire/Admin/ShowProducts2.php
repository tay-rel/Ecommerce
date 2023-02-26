<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;

class ShowProducts2 extends Component
{
    public $search;

    public function updatingSearch()
    {//cada vez que se busque se retorna a la pagina uno
        $this->resetPage();
    }
    public function render()
    {
        $products = Product::query()
            ->applyFilters([
                'search'=>$this->search
            ])->paginate(10);
        return view('livewire.admin.show-products2', compact('products')) ->layout('layouts.admin');
    }
}
