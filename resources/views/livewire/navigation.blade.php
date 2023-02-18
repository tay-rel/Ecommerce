<style>
    #navigation-menu{
        height: calc(100vh - 4rem);
    }
    .navigation-link:hover .navigation-submenu{
        display: block !important;
    }
</style>
<!-- Nosotros vamos a definir
la variable ‘open’ a ‘false’ que controlará que el elemento no sea mostrado.-->
<header class="bg-trueGray-700  sticky top-0 z-50"  style="z-index: 900"  x-data="dropdown()">
    <div class="container flex items-center h-16 justify-between md:justify-start">
        <!--a variable cambie a true y por tanto se muestre el listado.-->
        <a :class="{'bg-opacity-100 text-orange-500': open}" x-on:click="show()" class="flex flex-col items-center justify-center order-last md:order-first px-6 sm:px-4 bg-white bg-opacity-25 text-white cursor-pointer font-semibold h-full">
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-sm hidden sm:block">
                Categorías
            </span>
        </a>
        <a href="/" class="mx-6">
            <x-jet-application-mark class="block h-9 w-auto"></x-jet-application-mark>
        </a>

        <!-- Se oculta cuando la vista esta estrecha-->
        <div class="flex-1  hidden md:block">
            @livewire('search')
        </div>

        <!--Muestra un dropdown-->
        <div class="ml-3  mx-6 relative  hidden md:block">
            @auth
            <x-jet-dropdown align="right" width="48">
                <x-slot name="trigger">

                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        </button>
                </x-slot>

                <x-slot name="content">
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Account') }}
                    </div>

                    <x-jet-dropdown-link href="{{ route('profile.show') }}">
                        {{ __('Profile') }}
                    </x-jet-dropdown-link>

                    <x-jet-dropdown-link href="{{ route('orders.index') }}">
                        {{ __('My Orders') }}
                    </x-jet-dropdown-link>

                    <x-jet-dropdown-link href="{{ route('admin.index') }}">
                        {{ __('Admin') }}
                    </x-jet-dropdown-link>
                    <div class="border-t border-gray-100"></div>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-jet-dropdown-link href="{{ route('logout') }}"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-jet-dropdown-link>
                    </form>
                </x-slot>
            </x-jet-dropdown>
                <!--Cuando no estas logueado-->
            @else
                <x-jet-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <i class="fas fa-user-circle text-white text-3xl cursor-pointer"></i>
                    </x-slot>
                    <x-slot name="content">
                        <x-jet-dropdown-link href="{{ route('login') }}">
                            {{ __('Login') }}
                        </x-jet-dropdown-link>
                        <x-jet-dropdown-link href="{{ route('register') }}">
                            {{ __('Register') }}
                        </x-jet-dropdown-link>
                    </x-slot>
                </x-jet-dropdown>
            @endauth
        </div>

        <div class="hidden md:block">
            @livewire('dropdown-cart')
        </div>

    </div>
    <!--al actualizar vemos un recuadro gris que ocupa toda la ventana menos el menú.-->
    <nav id="navigation-menu" x-show="open"
         :class="{'block': open, 'hidden': !open}" class="bg-trueGray-700 bg-opacity-25 w-full absolute  hidden">

        <!--que está centrado y tiene la misma anchura que la barra de menú, por tanto usará la misma
        Se aplica la ultima clase para que no se muestre el menu en pantallas pequeñas-->

        <div class="container-menu h-full hidden sm:block ">
            <!--cuando hagamos click fuera de él, se cierre-->
            <div  x-on:click.away="close()" class="grid grid-cols-4 h-full">
                <ul class="bg-white">
                    @foreach($categories as $category)
                        <li class="navigation-link text-trueGray-500 hover:bg-orange-500 hover:text-white">
                            <a href="{{ route('categories.show', $category) }}" class="py-2 px-4 text-sm flex items-center">
                                <span class="flex justify-center w-9">
                                {!! $category->icon !!}
                                </span>
                                {{ $category->name }}
                            </a>
                            <div class="navigation-submenu bg-gray-100 absolute w-3/4 h-full top-0 right-0  hidden"><!--Hidden oculta el color rojo-->
                                <!--ara comprobar que cada vez que cambiamos de
categoría se cambia lo que mostramos-->
                                <x-navigation-subcategories :category="$category" />
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="col-span-3 bg-gray-100">
                    <x-navigation-subcategories :category="$categories->first()" />
                </div>
            </div>
        </div>

        <div class="bg-white h-full overflow-y-auto">
            <div class="container-menu bg-gray-200 py-3 mb-2">
                @livewire('search')
            </div>
            <ul class="bg-white">
                @foreach($categories as $category)
                    <li class="text-trueGray-500 hover:bg-orange-500 hover:text-white">
                        <a href="{{ route('categories.show', $category) }}" class="py-2 px-4 text-sm flex items-center">
                                <span class="flex justify-center w-9">
                                {!! $category->icon !!}
                                </span>
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <p class="text-trueGray-500 px-6 my-2">USUARIOS</p>

            @livewire('cart-movil')

            @auth
                <a href="{{ route('profile.show') }}" class="py-2 px-4 text-sm flex items-center text-trueGray-500 hover:bg-orange-500
hover:text-white">
                    <span class="flex justify-center w-9">
                    <i class="far fa-address-card"></i>
                    </span>
                    Perfil
                </a>
                <a href=""
                   onclick="event.preventDefault();
document.getElementById('logout-form').submit()"
                   class="py-2 px-4 text-sm flex items-center text-trueGray-500 hover:bg-orange-500 hover:text-white">
                    <span class="flex justify-center w-9">
                    <i class="fas fa-sign-out-alt"></i>
                    </span>
                    Cerrar sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="py-2 px-4 text-sm flex items-center text-trueGray-500 hover:bg-orange-500
hover:text-white">
                        <span class="flex justify-center w-9">
                        <i class="fas fa-user-circle"></i>
                        </span>
                    Iniciar sesión
                </a>
                <a href="{{ route('register') }}" class="py-2 px-4 text-sm flex items-center text-trueGray-500 hover:bg-orange-500
hover:text-white">
                    <span class="flex justify-center w-9">
                    <i class="fas fa-fingerprint"></i>
                    </span>
                    Registrar
                </a>
            @endauth
        </div>
    </nav>
</header>

<script>
    function dropdown(){
        return {
            open: false,
            show(){
                if(this.open){
                    this.open = false;
                    document.getElementsByTagName('html')[0].style.overflow = 'auto'
                }else{
                    this.open = true;
                    document.getElementsByTagName('html')[0].style.overflow = 'hidden'
                }
            },
            close(){
                this.open = false;
                document.getElementsByTagName('html')[0].style.overflow = 'auto'
            }
        }
    }

</script>
