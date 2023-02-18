<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-600">
            Lista de productos
        </h2>
    </x-slot>
<!--Buscador-->
    <div class="px-6 py-4">
        <x-jet-input class="w-full"
                     wire:model="search"
                     type="text"
                     placeholder="Introduzca el nombre del producto a buscar" />
    </div>

    <x-table-responsive>
        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Nombre</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Categoría</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Estado</th>
                                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Precio</th>
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
                                        <div class="h-10 w-10 flex-shrink-0 object-cover">
                                            <img class="h-10 w-10 rounded-full" src="{{ Storage::url($product->images->first()->url) }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <div class="text-gray-900">{{ $product->subcategory->category->name }}</div>
                                    <div class="text-gray-500">{{ $product->subcategory->name }}</div>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                    <span class="inline-flex rounded-full bg-{{ $product->status == 1 ? 'red' : 'green'
}}-100 px-2 text-xs font-semibold leading-5 text-{{ $product->status == 1 ? 'red' : 'green' }}-800">{{ $product->status == 1 ? 'Borrador' : 'Publicado' }}</span>
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $product->price }} &euro;</td>
                                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                </td>
                            </tr>
                            @endforeach
                            <!-- More people... -->
                            </tbody>
                        </table>
                    <!--Paginacion-->
                        <div class="px-6 py-4">
                            {{ $products->links() }}
                        </div>
    </x-table-responsive>

</div>
