<?php

namespace App\Queries;

class ProductBuilder extends QueryBuilder
{
    public function category($category)
    {
        return $this->whereHas('subcategory.category', function($query) use ($category){
            $query->where('id',$category);
        });
    }
    public function sort( $data)
    {
        return $this->join('brands', 'brands.id', 'brand_id')
            ->join('subcategories', 'subcategories.id', 'subcategory_id')
            ->join('categories', 'categories.id', 'category_id')
            ->select('products.*', 'categories.name as cName',
                'subcategories.name as sName', 'brands.name as bName',
                'products.quantity as stock', 'products.created_at as dateCreation')
            ->orderBy($data['field'], $data['direction'] ? 'asc' : 'desc');
    }

    public function color($id)
    {
        $this->whereHas('colors', function ($query) use ($id) {
            $query->where('colors.id', $id);
        })->orWhereHas('sizes', function ($query) use ($id) {
            $query->where(function ($query) use ($id) {
                $query->whereHas('colors', function ($query) use ($id) {
                    $query->where('color_id', $id);
                });
            });
        });
    }

    public function size($name)
    {
        $this->whereHas('sizes', function($q) use ($name){
            $q->where('sizes.name',$name);
        });
    }
}
