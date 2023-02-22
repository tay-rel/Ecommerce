<?php

namespace App\Http\Livewire;

use App\Models\City;
use App\Models\Department;
use App\Models\District;
use App\Models\Order;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class CreateOrder extends Component
{
    public $departments, $cities = [], $districts = [];
    public $department_id = '', $city_id = '', $district_id = '';
    public $address, $reference;
    public $envio_type = 1;
    public $contact, $phone,  $shipping_cost;

    public $rules = [
        'contact' => 'required',
        'phone' => 'required',
        'envio_type' => 'required'
    ];

    public function create_order()
    {
        $rules = $this->rules;
        if ($this->envio_type == 2) {
            $rules['department_id'] = 'required';
            $rules['city_id'] = 'required';
            $rules['district_id'] = 'required';
            $rules['address'] = 'required';
            $rules['reference'] = 'required';
        }
        $this->validate($rules);

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->contact = $this->contact;
        $order->phone = $this->phone;
        $order->envio_type = $this->envio_type;
        $order->shipping_cost = 0;
        $order->total = $this->shipping_cost + Cart::subtotal();
        $order->content = Cart::content();

        if ($this->envio_type == 2) {
            $order->shipping_cost = $this->shipping_cost;
            $order->envio = json_encode([
                'department' => Department::find($this->department_id)->name,//guarda o recupera el registro con id
                'city' => City::find($this->city_id)->name,
                'district' => District::find($this->district_id)->name,
                'address' => $this->address,
                'reference' => $this->reference
            ]);
        }

        $order->save();//se crea un nuevo registro

        //el encargado de hacer la modificación en la bbdd
        foreach (Cart::content() as $item) {
            discount($item);
        }

        Cart::destroy();
        return redirect()->route('orders.payment', $order);
    }
    public function mount()
    {
        $this->departments = Department::all();
    }

    public function updatedEnvioType($value)
    {
        if ($value == 1) {
            $this->resetValidation([
                'department_id',
                'city_id',
                'district_id',
                'address',
                'reference',
            ]);
        }
    }

    // Al seleccionar una ciudad se deben cargar
    //los distritos asociados.
    public function updatedDepartmentId($value){
        $this->cities = City::where('department_id', $value)->get();
        $this->reset(['city_id',
            'district_id'
        ]);
    }
    public function updatedCityId($value){
        $city = City::find($value);
        $this->shipping_cost = $city->cost;

        $this->districts = District::where('city_id', $value)->get();
        $this->reset('district_id');
    }

    public function render()
    {
        return view('livewire.create-order');
    }
}
