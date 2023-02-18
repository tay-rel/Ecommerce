 <?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WelcomeController;
use App\Http\Livewire\CreateOrder;
 use App\Http\Livewire\PaymentOrder;
 use App\Http\Livewire\ShoppingCart;
 use App\Models\Order;
 use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', WelcomeController::class);

Route::get('search', SearchController::class)->name('search');

//apunta los enlaces
Route::get('categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('products/{product}', [ProductsController::class, 'show'])->name('products.show');

Route::get('shopping-cart', ShoppingCart::class)->name('shopping-cart');

 Route::middleware(['auth'])->group(function (){//autentica las vista a través del mildware
     //nos lleva a los pedidos
     Route::get('orders', [OrderController::class, 'index'])->name('orders.index');

     Route::get('orders/create', CreateOrder::class)->name('orders.create');

     Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');

     Route::get('orders/{order}/payment', PaymentOrder::class)->name('orders.payment');

     //Obtiene todos los pedidos pendientes de nuestra aplicacion, las ordenes que se han creado hace 10 mint o antes
     Route::get('prueba', function () {

         $orders = Order::where('status', 1)
             ->where('created_at','<',now()->subMinutes(10))->get();

         //itera cada una de las ordenes
         foreach ($orders as $order) {
             //donde se enceuntra el lstado de productoos en formato string
             $items = json_decode($order->content);
             //recorre el listado y cada uno de los elementos que contiene
                 foreach ($items as $item) {
                     increase($item);//incrementa el stock en la misma cantidad que tenia el producto que esta en el helper
                 }
             $order->status = 5;
             $order->save();
         }
         return "Completado con éxito";
     });
 });




