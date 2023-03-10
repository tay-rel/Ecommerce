<?php

namespace App\Http\Livewire\Admin;

use App\Filters\ProductFilter;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProducts2 extends Component
{
    use WithPagination;


    public $search;
    public $priceMin,$priceMax;
    public $category;
    public $subcategory;

    public $subcategories = [];

    public $pages = [5,10,15,25,50,100];
    public $selectPage=5;

    public $columns = ['Imagen', 'Nombre','Categoria','Estado','Precio','Marca','Ventas', 'Stock', 'Fecha'];
    public $selectedColumn = [];

    public $sortField = 'name';
    public $sortAsc = 'asc';


    public function mount()
    {
        $this->selectedColumn = ['Imagen', 'Nombre','Categoria','Estado','Precio','Marca','Ventas', 'Stock', 'Fecha'];
        $this->priceMin = Product::min('price');
        $this->priceMax =Product::max('price');
        $this->getSubcategories();
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

    public function sortBy($field)
    {
        $this->sortAsc = ($this->sortField === $field) ? !$this->sortAsc : 'asc';
        $this->sortField = $field;
    }

    public function sortIcon($field, $currentField, $currentAsc)
    {
        if ($field === $currentField) {
            if ($currentAsc === 'asc') {
                return '<i class="fas fa-arrow-up p-4"></i>';
            } else {
                return '<i class="fas fa-arrow-down p-4"></i>';
            }
        } else {
            return '<i class="fas fa-arrows-alt-v p-4"></i>';
        }
    }
    public function getProducts(ProductFilter $productFilter)
    {
        $products = Product::query()
            ->filterBy($productFilter,[
                'search' => $this->search,
                'sort' => ['field' => $this->sortField, 'direction' => $this->sortAsc],
                'price'=>[$this->priceMin,$this->priceMax],
                'category'=>$this->category,
                'subcategory' => $this->subcategory,
            ])->paginate($this->selectPage);

        $products->appends($productFilter->valid());
        return $products;
    }

    public function getSubcategories()
    {
        $this->subcategories = Subcategory::where('category_id', $this->category)->get();
    }


    public function render(ProductFilter $productFilter)
    {
        $products =  $this->getProducts($productFilter);
        return view('livewire.admin.show-products2', [
                'products'=>$products,
                'categories'=> Category::get(),
        ]
        ) ->layout('layouts.admin');
    }
}
