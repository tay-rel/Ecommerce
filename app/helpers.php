<?php
/* Los helpers son funciones definidas por nosotros,
sin una clase en la que se encuentren y que pueden ser utilizados
 a lo largo de toda la aplicación*/

//Esta función la utilizamos para calcular cualquier cantidad del producto
use App\Models\Product;
use App\Models\Size;
use Gloudemans\Shoppingcart\Facades\Cart;

function quantity($product_id, $color_id = null, $size_id = null)
{
    //colo_id y size_id son opcionales
$product = Product::find($product_id);

    If ($size_id) {
        $size = Size::find($size_id);//vamos a buscar la información de la talla
        //recuperamos el color de la talla que hemos seleccionado
        $quantity = $size->colors->find($color_id)->pivot->quantity;
    } elseif ($color_id) {
        //en el caso que no hemos enviado la información de la talla y color pedimos que recupere la relación de product color
        $quantity = $product->colors->find($color_id)->pivot->quantity;
    } else {
        $quantity = $product->quantity;
    }
return $quantity;
}
function qty_added($product_id, $color_id = null, $size_id = null)
{
    //cart recupera todos los productos que hemos almacenado en compra
    $cart = Cart::content();
    //hacemos una serie de filtros para buscar los productoss
    $item = $cart->where('id', $product_id)
                ->where('options.color_id', $color_id)
                ->where('options.size_id', $size_id)
                ->first();//Especificamos que lo que retorne en la variable no se trata de una colección sino un objetos
    if ($item) {
        return $item->qty;
    } else {
        return 0;
    }
}
//esta funcion devuelve la cantidad que aún puedo agregar a mi compra
function qty_available($product_id, $color_id = null, $size_id = null){
    return quantity($product_id, $color_id, $size_id) - qty_added($product_id, $color_id, $size_id);//retorna la resta
}

    function discount($item)
    {
        $product = Product::find($item->id);
        $qty_available = qty_available($item->id, $item->options->color_id, $item->options->size_id);
            if ($item->options->size_id) {
                $size = Size::find($item->options->size_id);
                $size->colors()->detach($item->options->color_id);
                $size->colors()->attach([
                    $item->options->color_id => ['quantity' => $qty_available]
                ]);
            } elseif($item->options->color_id) {
                $product->colors()->detach($item->options->color_id);
                $product->colors()->attach([
                    $item->options->color_id => ['quantity' => $qty_available]
                ]);
            } else {
                $product->quantity = $qty_available;
                $product->save();
            }

        function increase($item)
        {
            $product = Product::find($item->id);
            $quantity = quantity($item->id, $item->options->color_id, $item->options->size_id) + $item->qty;

            if ($item->options->size_id) {
                $size = Size::find($item->options->size_id);
                $size->colors()->detach($item->options->color_id);
                $size->colors()->attach([
                    $item->options->color_id => ['quantity' => $quantity]
                ]);
            } elseif($item->options->color_id) {
                $product->colors()->detach($item->options->color_id);
                $product->colors()->attach([
                    $item->options->color_id => ['quantity' => $quantity]
                ]);
            } else {
                $product->quantity = $quantity;
                $product->save();
            }
    }
}
