<?php

namespace App\Filters;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

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
         'from' => 'filled|date_format:Y-m-d',
         'to' => 'filled|date_format:Y-m-d',
        // 'stock' => ['filled', Rule::in([0, 1, 2])]
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
     $query->category($category);
    }

    public function subcategory($query,$subcategory)
    {
        return $query->where('subcategory_id', $subcategory);
    }

    public function brand($query, $brand)
    {
        return $query->where('brand_id', $brand);
    }
    public function from($query, $date)
    {
        $query->whereDate('products.created_at', '>=', $date);
    }

    public function to($query, $date)
    {
        $query->whereDate('products.created_at', '<=', $date);
    }

//    public function stock($query, $stock)
//    {
//        $range = config('stock')['stock'][$stock];
//
//        return $query->whereBetween('quantity', [$range[0], $range[1]])
//            ->whereHas('subcategory', function ($query) {
//                $query->where('color', false);
//            })
//
//            ->orWhere(function ($query) use ($range) {
//                $query->orWhereHas('colors', function ($query) use ($range) {
//                    $query->groupBy('product_id')->havingRaw('sum(quantity) >= ? and sum(quantity) < ?', [$range[0], $range[1]]);
//                })
//                    ->whereHas('subcategory', function ($query) {
//                        $query->where('color', true)->where('size', false);
//                    });
//            })
//            ->orWhereHas('sizes.colors', function ($query) use ($range) {
//                $query->groupBy('size_id')->havingRaw('sum(quantity) >= ? and sum(quantity) < ?', [$range[0], $range[1]]);
//            });
//    }
    public function sort($query, $data)
    {
        $query->sort($data);
    }
}
