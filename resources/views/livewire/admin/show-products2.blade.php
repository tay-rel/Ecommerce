<div class="container-menu">
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-600 leading-tight">
                Lista de productos 2
            </h2>
            <x-button-link class="ml-auto" href="{{route('admin.products.create')}}">
                Agregar producto
            </x-button-link>
        </div>
    </x-slot>


    <x-table-responsive>
        <!--Buscador-->

        <div class="px-6 py-4">
            <x-jet-input class="w-full"
                         wire:model="search"
                         type="text"
                         placeholder="Introduzca el nombre del producto a buscar" />
        </div>
        {{--Filtros--}}
        <div class="bg-white rounded border shadow-md p-2 px-4 font-normal z-99">
            @include('livewire.admin.partials._filters')
        </div>
        @if($products->count())
            <table class="min-w-full divide-y divide-gray-300">
                <div class="flex  justify-between p-5">
                    {{--Seleccionar paginacion--}}
                    <div class="flex items-center ">
                        <div class="mb-3 xl:w-96">
                            <select class="form-control" wire:model="selectPage">
                                <option value="" selected disabled>Seleccione paginación</option>
                                @foreach ($pages as $page)
                                    <option value="{{ $page }}">{{ $page }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{--Visibilidad de columnas--}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center justify-between bg-indigo-500  px-4 py-2 text-sm font-medium text-white  rounded-md  focus:outline-none   focus:ring-indigo-500">
                            <span>Visibilidad de columnas</span>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute z-10 w-full mt-2 bg-white rounded-md shadow-lg">
                            @foreach($columns as $column)
                                <label class="block px-4 py-2 text-sm font-medium text-gray-700">
                                    <input type="checkbox" wire:model="selectedColumn" value="{{ $column }}" class="mr-2 leading-tight">
                                    <span class="text-gray-900">{{ $column }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    {{--LImpia filtros--}}
                    <div>
                        <button wire:click="clearFilters()" class="flex items-center justify-between bg-indigo-500  px-4 py-2 text-sm font-medium text-white  rounded-md  focus:outline-none   focus:ring-indigo-500">
                            <span>Limpiar Filtros</span>
                        </button>
                    </div>

                </div>

                <thead class="bg-gray-50">
                <tr>
                    @if($this->showColumns('Nombre'))
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                        <a wire:click.prevent="sortBy('name')" role="button" href="#">
                            Nombre
                            {!! \App\Http\Livewire\Admin\ShowProducts2::sortIcon('name', $sortField, $sortAsc) !!}
                        </a>
                    @endif
                    </th>
                    @if($this->showColumns('Categoria'))
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                        <a wire:click.prevent="sortBy('cName')" role="button" href="#">
                            Categoría
                            {!! \App\Http\Livewire\Admin\ShowProducts2::sortIcon('cName', $sortField, $sortAsc) !!}
                        </a>
                    @endif
                    </th>
                    @if($this->showColumns('Estado'))
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                <a wire:click.prevent="sortBy('status')" role="button" href="#">
                                    Estado
                            {!! \App\Http\Livewire\Admin\ShowProducts2::sortIcon('status', $sortField, $sortAsc) !!}
                        </a>
                    @endif
                    </th>
                    @if($this->showColumns('Precio'))
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                <a wire:click.prevent="sortBy('price')" role="button" href="#">
                                    Precio
                            {!! \App\Http\Livewire\Admin\ShowProducts2::sortIcon('price', $sortField, $sortAsc) !!}
                        </a>
                    @endif
                    </th>

                    @if($this->showColumns('Marca'))
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                <a wire:click.prevent="sortBy('bName')" role="button" href="#">
                                    Marca
                            {!! \App\Http\Livewire\Admin\ShowProducts2::sortIcon('bName', $sortField, $sortAsc) !!}
                        </a>
                    @endif
                    </th>
                    @if($this->showColumns('Ventas'))
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                <a wire:click.prevent="sortBy('')" role="button" href="#">
                                    Nº Ventas
                            {!! \App\Http\Livewire\Admin\ShowProducts2::sortIcon('', $sortField, $sortAsc) !!}
                        </a>
                    @endif
                    </th>
                    @if($this->showColumns('Stock'))
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                <a wire:click.prevent="sortBy('stock')" role="button" href="#">
                                    Stock disponible
                            {!! \App\Http\Livewire\Admin\ShowProducts2::sortIcon('stock', $sortField, $sortAsc) !!}
                        </a>
                    @endif
                    </th>
                    @if($this->showColumns('Fecha'))
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                <a wire:click.prevent="sortBy('dateCreation')" role="button" href="#">
                                    Fecha de creación
                            {!! \App\Http\Livewire\Admin\ShowProducts2::sortIcon('dateCreation', $sortField, $sortAsc) !!}
                        </a>
                    @endif
                    </th>

                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Editar</span>
                    </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                @foreach($products as $product)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center">
                                @if($this->showColumns('Imagen'))
                                <div class="h-10 w-10 flex-shrink-0 object-cover">
                                    <img class="h-10 w-10 rounded-full" src="{{  $product->images->count() ? Storage::url($product->images->first()->url) :
'img/default.jpg' }}" alt="">
                                </div>
                                @endif
                                @if($this->showColumns('Nombre'))
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                </div>
                                    @endif
                            </div>
                        </td>
                        @if($this->showColumns('Categoria'))
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            <div class="text-gray-900">{{ $product->subcategory->category->name }}</div>
                            <div class="text-gray-500">{{ $product->subcategory->name }}</div>
                            @endif
                        </td>
                            @if($this->showColumns('Estado'))
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span class="inline-flex rounded-full bg-{{ $product->status == 1 ? 'red' : 'green'
                                        }}-100 px-2 text-xs font-semibold leading-5 text-{{ $product->status == 1 ? 'red' : 'green' }}-800">{{ $product->status == 1 ? 'Borrador' : 'Publicado' }}</span>
                            @endif
                        </td>
                                @if($this->showColumns('Precio'))
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->price }} &euro;
                            @endif
                        </td>
                                    @if($this->showColumns('Marca'))
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->brand->name }}
                            @endif
                        </td>
                                        @if($this->showColumns('Ventas'))
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">{{ $product->sales }}
                            @endif
                        </td>
                                            @if($this->showColumns('Stock'))
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">{{ $product->stock }}
                            @endif
                        </td>
                                                @if($this->showColumns('Fecha'))
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-center">{{ $product->created_at->translatedFormat('D M Y H:m') }}
                            @endif
                        </td>

                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                        </td>
                    </tr>
                @endforeach
                <!-- More people... -->
                </tbody>
            </table>
        @else
            <div class="px-6 py-4">
                No existen productos coincidentes
            </div>
        @endif
        <!--Paginacion-->
        @if($products->hasPages())
            <div class="px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </x-table-responsive>

</div>
