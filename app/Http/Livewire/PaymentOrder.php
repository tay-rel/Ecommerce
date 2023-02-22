<?php

namespace App\Http\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentOrder extends Component
{
    use AuthorizesRequests;
    public $order;
    protected $listeners = ['payOrder'];//escucha el evento que estamos pidiendos

    public function mount(Order $order)
    {
        $this->order = $order;
    }
    public function payOrder()
    {
        $this->order->status = 2;//recibido
        $this->order->save();//actualizamos en la bbdd y lo guardamos
        return redirect()->route('orders.show', $this->order);
    }
    public function render()
    {
        //en caso de no estar autenticado no vera la pagina
        $this->authorize('view', $this->order);

        $items = json_decode($this->order->content);
        $envio = json_decode($this->order->envio);
        return view('livewire.payment-order', compact('items','envio'));
    }
}
