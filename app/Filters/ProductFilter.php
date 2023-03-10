<?php

namespace App\Filters;

use App\Models\Product;

class ProductFilter extends QueryFilter
{

    public function rules(): array
    {
     return [
         'search' => 'filled',
         'sort' => 'filled|array',
         'price' => 'array',
         'price.0' => 'lte:' . Product::max('price'),
         'price.1' => 'gte:' . Product::min('price'),
        ];
    }
    public function search($query, $search)
    {
        return  $query->where('name', 'like', "%{$search}%");
    }

    public function price($query, $price)
    {
        return $query->whereBetween('price', [$price[0],$price[1]]);
    }
    public function sort($query, $data)
    {
        $query->join('brands', 'brands.id', 'brand_id')
            ->join('subcategories', 'subcategories.id', 'subcategory_id')
            ->join('categories', 'categories.id', 'category_id')
            ->select('products.*', 'categories.name as cName',
                'subcategories.name as sName', 'brands.name as bName',
                'products.quantity as stock', 'products.created_at as dateCreation')
            ->orderBy($data['field'], $data['direction'] ? 'asc' : 'desc');
    }
}
