<?php

namespace App\Filters;

class ProductFilter extends QueryFilter
{

    public function rules(): array
    {
     return [
         'search' => 'filled',
     ];
    }
    public function Search($query, $search)
    {
        return  $query->where('name', 'like', "%{$search}%");
    }
}
