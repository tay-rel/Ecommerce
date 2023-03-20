<?php

namespace App\Models;

use App\Filters\ProductFilter;
use App\Filters\QueryFilter;
use App\Queries\ProductBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    protected $fillable = ['name', 'slug', 'description', 'price', 'subcategory_id', 'brand_id', 'quantity'];
    //protected $guarded = ['id', 'created_at', 'updated_at'];
    public function newEloquentBuilder($query)
    {
        return new ProductBuilder($query);
    }

    public function newQueryFilter()
    {
        return new ProductFilter();
    }

    public function sizes(){
        return $this->hasMany(Size::class);
    }
    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    public function subcategory(){
        return $this->belongsTo(Subcategory::class);
    }
    public function colors(){
        return $this->belongsToMany(Color::class)->withPivot('quantity', 'id');//obtenemos el id de la tabla
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    //accesores , que me devuelve el stock del producto que eligo
    public function getStockAttribute()
    {
        if ($this->subcategory->size) {//si me da true , necesita que de talla y color
            return ColorSize::whereHas('size.product', function (Builder $query) {//hacer consultas a las relaciones
                $query->where('id', $this->id);
            })->sum('quantity');//que sume lo que tenemos almacenado
        } elseif ($this->subcategory->color) {//esto da color
            return ColorProduct::whereHas('product', function (Builder $query) {
                $query->where('id', $this->id);
            })->sum('quantity');
        } else {
            return $this->quantity;
        }
    }

    public function getSalesAttribute()
    {
        $productId = $this->id;
        $sales=0;

        $orders = Order::select('content')->where('status', 2)->get()->map(function ($order){
            return json_decode($order->content, true);
        });

        $products = $orders->collapse();

            foreach ($products as $product){
                if ($product['id'] == $productId) {
                    $sales += $product['qty'];
                }
            }
            return $sales;
        }

    public function getUnconfirmedAttribute()
    {
        $id = $this->id;
        $orders = Order::select('content')->where('status', 1)->get();
        $i = 0;

        foreach($orders as $order) {
            $orders[$i] = json_decode($order->content, true);
            $i++;
        }
        $products = $orders->collapse();
        $counter = 0;
        foreach ($products as $product) {

            if ($product['id'] == $id) {
                $counter = $counter + $product['qty'];
            };
        }
        return $counter;
    }

     public function getColorQuantitiesAttribute()
    {
        $colorQuantities = [];

        if (!$this->subcategory->color) {//sin color
            $colorQuantities['No tiene '] = 'Color';
        } else{
            if (!$this->subcategory->size) {//color
                foreach ($this->colors as $color) {
                    $colorQuantities[$color->name] = $color->pivot->quantity;
                }
            } else {
                foreach ($this->sizes as $size) {//color y talla
                    foreach ($size->colors as $color) {
                        $colorQuantities[$color->name] = $color->pivot->quantity;
                    }
                }
            }
        }

        return $colorQuantities;
    }
    public function getSizeQuantitiesAttribute()
    {
        $sizeQuantities = [];

        if (!$this->subcategory->size) {//sin talla
            $sizeQuantities['No tiene '] = 'Talla';
        } else{
                foreach ($this->sizes as $size) {//color y talla
                        $sizeQuantities[$size->name] =  array_sum($size->colors->pluck('pivot')->pluck('quantity')->all());;
                }
        }

        return $sizeQuantities;
    }
}
