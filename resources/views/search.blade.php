<x-app-layout>

    <ul><!--Itera la colección products-->
        @foreach($products as $product)
            <li>{{ $product->name }}</li>
        @endforeach
    </ul>

</x-app-layout>
