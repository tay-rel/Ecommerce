<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts extends Component
{
    use WithPagination;

    public $search;

    public function updatingSearch()
    {//cada vez que se busque se retorna a la pagina uno
        $this->resetPage();
    }
    public function render()
    {
        // medida que nosotros vamos escribiendo en el input,
        //queremos que el contenido de la tabla vaya cambiando
//        $products = Product::where('name', 'LIKE', "%{$this->search}%")->paginate(10);

        $products = Product::query()
            ->applyFilters([
                'search'=>$this->search
            ])->paginate(10);

            return view('livewire.admin.show-products', compact('products'))
            ->layout('layouts.admin');
    }
}
