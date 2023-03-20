<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\User;

trait CreateData
{

    public function createUser()
    {
        return User::factory()->create();
    }
    public function createCategory()
    {
        return Category::factory()->create([
            'name'=>'Menu',
        ]);
    }

    public function createSubcategory($category, $color =false, $size=false)
    {
        return Subcategory::factory()->create([
            'category_id'=>$category->id,
            'color'=>$color,
            'size'=>$size
        ]);
    }

    public function createBrand($category)
    {
        $brand =Brand::factory()->create();
        $category->brands()->attach($brand->id);
        return $brand;
    }

    public function createColor()
    {
        return Color::factory()->create();
    }

    public function createSize($product)
    {
        return Size::factory()->create([
            'product_id'=>$product->id
        ]);
    }

    public function createProduct($color=false,$size =false,$quantity = 5, $price = 20, $category_id = null, $numImages = 1)
    {
        $category = $this->createCategory();

        if($category_id){
            $category->id = $category_id;
        }

        $subcategory = $this->createSubcategory($category, $color, $size);

        $brand = $this->createBrand($category);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'quantity' => $quantity,
            'price' => $price
        ]);

        Image::factory($numImages)->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        if ($size && $color) {
            $product->quantity = null;
            $productColor = $this->createColor();
            $productSize = $this->createSize($product);
            $productSize->colors()->attach($productColor->id, ['quantity' => $quantity]);
        } elseif ($color && !$size) {
            $product->quantity = null;
            $productColor = $this->createColor();
            $product->colors()->attach($productColor->id, ['quantity' => $quantity]);
        }
        return $product;
    }

    public function createOnlyProduct($name)
    {
        $category = Category::factory()->create();
        $subcategory = Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => false,
            'size' => false,
        ]);

        $brand = Brand::factory()->create();
        $category->brands()->attach([$brand->id]);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'name' => $name,
            'brand_id' => $brand->id,
            'quantity' => 2
        ]);
        Image::factory(2)->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);
        return $product;
    }
}
