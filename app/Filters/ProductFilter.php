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
         'category' => 'filled|exists:categories,id',
         'subcategory' => 'filled|exists:subcategories,id',
         'brand' => 'filled|exists:brands,id',
        ];
    }
    public function search($query, $search)
    {
        return  $query->where('products.name', 'like', "%{$search}%");
    }

    public function price($query, $price)
    {
        return $query->whereBetween('price', [$price[0],$price[1]]);
    }

    public function category($query,$category)
    {
        return $query->whereHas('subcategory.category', function($query) use ($category){
            $query->where('id',$category);
        });
    }

    public function subcategory($query,$subcategory)
    {
        return $query->where('subcategory_id', $subcategory);
    }

    public function brand($query, $brand)
    {
        return $query->where('brand_id', $brand);
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
