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
        $this->whereHas('colors', function($q) use ($id){
            $q->where('colors.id',$id);
        })->orWhereHas('sizes.colors', function($q) use ($id){
            $q->where('colors.id', $id);
        })->get();//para ejecutar la consulta y devolver los resultados.
    }

}
