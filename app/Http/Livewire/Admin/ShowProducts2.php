<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts2 extends Component
{
    use WithPagination;


    public $search;

    public $pages = [5,10,15,25,50,100];
    public $selectPage=5;

    public $columns = ['Imagen', 'Nombre','Categoria','Estado','Precio','Marca','Ventas', 'Stock', 'Fecha'];
    public $selectedColumn = [];

    public function mount()
    {
        $this->selectedColumn = ['Imagen', 'Nombre','Categoria','Estado','Precio','Marca','Ventas', 'Stock', 'Fecha'];
    }

    public function showColumns($column)
    {
        return in_array($column, $this->selectedColumn);
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectPage()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::where('name', 'LIKE', "%{$this->search}%")->paginate($this->selectPage);
        return view('livewire.admin.show-products2', compact('products')) ->layout('layouts.admin');
    }
}
