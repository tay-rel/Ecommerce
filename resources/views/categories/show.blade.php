<x-app-layout>
    <div class="container-menu py-8">
        <!--Cuando se le da click a una categoria se nos muestra la imagen del producto -->
        <figure class="mb-4">
            <img class="w-full h-80 object-cover object-center" src="{{ Storage::url($category->image) }}" alt="">
        </figure>
        @livewire('category-filter', ['category' => $category])
    </div>
</x-app-layout>
