<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use Livewire\Component;

class ColorProduct extends Component
{
    protected $rules = [
        'color_id' => 'required',
        'quantity' => 'required|numeric'
    ];
    public $product, $colors;
    public $color_id, $quantity;
    public function mount()
    {
        $this->colors = Color::all();
    }

    public function save()
    {
        $this->validate();
        $this->product->colors()->attach([$this->color_id => ['quantity' => $this->quantity]]);
        $this->reset(['color_id', 'quantity']);
        $this->emit('saved');
        $this->product = $this->product->fresh();//agreguemos una nueva fila debemos refrescar los datos del producto

    }

        public function render()
    {
        $productColors = $this->product->colors;
        return view('livewire.admin.color-product', compact('productColors'));
    }
}
