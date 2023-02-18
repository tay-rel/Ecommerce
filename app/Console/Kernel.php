<?php

namespace App\Console;

use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        //horario =programar una tarea para que se ejecuta en un cierto tiempo
//        $schedule->call(function () {
//            $orders = Order::where('status', 1)->where('created_at','<',now()->subMinutes(10))->get();
//            //itera cada una de las ordenes
//            foreach ($orders as $order) {
//                //donde se enceuntra el lstado de productoos en formato string
//                $items = json_decode($order->content);
//                //recorre el listado y cada uno de los elementos que contiene
//                foreach ($items as $item) {
//                    increase($item);//incrementa el stock en la misma cantidad que tenia el producto que esta en el helper
//                }
//                $order->status = 5;
//                $order->save();
//            }
//        })->everyMinute();

        $schedule->call(function () {
            $orders = Order::where('status', 1)->where('created_at','<',now()->subMinutes(10))->get();
            foreach ($orders as $order) {
                $items = json_decode($order->content);
                foreach ($items as $item) {
                    increase($item);
                }
                $order->status = 5;
                $order->save();
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
