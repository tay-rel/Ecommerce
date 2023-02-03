<?php

namespace App\Providers;

use App\Listeners\MergeTheCart;
use App\Listeners\MergeTheCartLogout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;//obtien el carrito autenticado
use Illuminate\Auth\Events\Logout;//almacena
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */

    //cada vez que se use uun evento se deb generar un oyente
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [//evento
            MergeTheCart::class,//oyente
        ],
        Logout::class => [
            MergeTheCartLogout::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
