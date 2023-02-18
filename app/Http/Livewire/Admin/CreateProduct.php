<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateProduct extends Component
{
    public $categories,  $subcategories = [], $brands = [];
    public $category_id = '' , $subcategory_id = '', $brand_id = '';
    public $name, $slug,  $description, $price, $quantity;
    public function mount()
    {
        $this->categories = Category::all();
    }
    public function updatedCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();

        //marcas asociadas a las categorias que eh asociado
        $this->brands = Brand::whereHas('categories', function(Builder $query) use ($value) {//usa la vaariable dentro de la funcion que lleva como parametro
            $query->where('category_id', $value);
        })->get();

        $this->reset(['subcategory_id', 'brand_id']);//cada que se actualice una categoria se resetea todo
    }
    public function updatedName($value){
        $this->slug = Str::slug($value);
    }
    //propiedad computada
    public function getSubcategoryProperty()
    {//busca la subcategory y busca cuyo id coincida
        return Subcategory::find($this->subcategory_id);
    }
    public function render()
    {
        return view('livewire.admin.create-product')->layout('layouts.admin');
    }
}
