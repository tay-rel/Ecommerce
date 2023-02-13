<?php

namespace App\Http\Livewire;

use App\Models\Department;
use Livewire\Component;

class CreateOrder extends Component
{
    public $departments, $cities = [], $districts = [];
    public $department_id = '', $city_id = '', $district_id = '';
    public $address, $reference;
    public $envio_type = 1;
    public $contact, $phone;

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
    }
    public function mount()
    {
        $this->departments = Department::all();
    }

    /* Si el usuario elige la opción de Envío a domicilio y presiona el botón
sin rellenar los campos específicos, la validación no pasa*/

    public function updatedEnvioType($value)
    {
        //$envio_type y si su valor
        //es 1 (si cambia es porque era 2) entonces eliminamos los errores de validación de los campos:
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

    public function render()
    {
        return view('livewire.create-order');
    }
}
