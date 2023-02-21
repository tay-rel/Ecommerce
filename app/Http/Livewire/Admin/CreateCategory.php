<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
class CreateCategory extends Component
{
    use WithFileUploads;
    public $brands, $categories, $image;//image limpia el input de subida de imagen
    public $createForm = [
        'name' => null,
        'slug' => null,
        'icon' => null,
        'image' => null,
        'brands' => [],
    ];
    protected $rules = [
        'createForm.name' => 'required',
        'createForm.slug' => 'required|unique:categories,slug',
        'createForm.icon' => 'required',
        'createForm.image' => 'required|image|max:1024',
        'createForm.brands' => 'required',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.slug' => 'slug',
        'createForm.icon' => 'icono',
        'createForm.image' => 'imagen',
        'createForm.brands' => 'marcas',
    ];
    public $category;
    public $editImage;
    public $editForm = [
        'open' => false,
        'name' => null,
        'slug' => null,
        'icon' => null,
        'image' => null,
        'brands' => [],
    ];
    public $listeners = ['delete'];

    public function mount()
    {
        $this->getBrands();
        $this->getCategories();
        $this->image = 1;
    }
    public function getBrands()
    {
        $this->brands = Brand::all();
    }
    public function save()
    {
        $this->validate();

        $image = $this->createForm['image']->store('categories', 'public');//vrea una imagen y subirla
        $category = Category::create([
            'name' => $this->createForm['name'],
            'slug' => $this->createForm['slug'],
            'icon' => $this->createForm['icon'],
            'image' => $image
        ]);
        $category->brands()->attach($this->createForm['brands']);

        $this->image = 2;
        $this->reset('createForm');

        $this->getCategories();//aparece en el listado
        $this->emit('saved');
    }
    public function updatedCreateFormName($value)
    {
        $this->createForm['slug'] = Str::slug($value);
    }
    public function edit(Category $category)
    {
        $this->image = rand();
        $this->reset(['editImage']);

        $this->category = $category;

        $this->editForm['open'] = true;
        $this->editForm['name'] = $category->name;
        $this->editForm['slug'] = $category->slug;
        $this->editForm['icon'] = $category->icon;
        $this->editForm['image'] = $category->image;
        $this->editForm['brands'] = $category->brands->pluck('id');//trae uno de los campos ,es una coleccion
    }
    public function getCategories()
    {
        $this->categories = Category::all();
    }
    public function delete(Category $category)
    {
        $category->delete();
        $this->getCategories();
    }
    public function render()
    {
        return view('livewire.admin.create-category');
    }
}