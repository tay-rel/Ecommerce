<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // procesar el valor de ese parámetro
        // y poder filtrar el listado de pedidos
        $orders = Order::query()->where('user_id', auth()->user()->id);
        if (request('status')) {
            $orders->where('status', request('status'));
        }
        $orders = $orders->get();

        for ($i = 1; $i <= 5; $i++) {
            $ordersByStatus[$i] = Order::where('user_id', auth()->user()->id)->where('status', $i)->count();
        }
        return view('orders.index', compact('orders', 'ordersByStatus'));
    }

    public function show(Order $order)
    {   $this->authorize('view', $order);//filtra la vista

        $items = json_decode($order->content);
        $envio = json_decode($order->envio);
        return view('orders.show', compact('order','items','envio'));
    }
}
