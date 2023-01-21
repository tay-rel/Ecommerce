<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class CategoryFilter extends Component
{
    //la paginacion es dinamica,
    use WithPagination;

    //Da informacion al componente
    public $category , $subcategoria, $marca;
    public function limpiar()
    {
        $this->reset(['subcategoria', 'marca']);
    }

    public function render()
    {
        $products = $this->category
            ->products()
            ->where('status', 2)
            ->paginate(20);

        return view('livewire.category-filter', compact('products'));
    }
}
