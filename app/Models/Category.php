<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected  $fillable = ['name','slug','image','icon'];

    public function subcategories(){
        return $this->hasMany(Subcategory::class);
    }
    public function brands(){
        return $this->belongsToMany(Brand::class);
    }

    public function products(){
        return $this->hasManyThrough(Product::class, Subcategory::class);
    }

    //Debemos usar URLs
    //amigables. Y recordemos que cada categoría tenía un slug.
    public function getRouteKeyName()
    {
        //le indicamos que debe mostrar el slug y no el id
        return 'slug';
    }
}
