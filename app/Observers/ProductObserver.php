<?php

namespace App\Observers;

use App\Models\Product;

/*
Cada vez que un producto sea actualizado, se compruebe si
se deben eliminar elementos relacionados con las tablas pivote de color o tallas.
Si el producto tiene talla,
hemos de eliminar la relación con colores (si no hay colores asociados de la categoría previa, no hace nada).
Si el producto tiene color, hemos de eliminar las tallas que tuviera (en caso de que la subcategoría previa
las tuviera). Y si no tiene ni talla ni color, eliminamos las posibles relaciones con color y con talla, pues
podría tener una de las dos (o ninguna, pero no las dos a la vez)
*/
class ProductObserver
{
    public function updated(Product $product)
    {
        $subcategory = $product->subcategory;
        if ($subcategory->size) {
            $product->colors()->detach();
        } elseif ($subcategory->color) {
            foreach ($product->sizes as $size) {
                $size->delete();
            }
        } else {
            $product->colors()->detach();
            foreach ($product->sizes as $size) {
                $size->delete();
            }
        }
    }
}
