<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function files(Product $product, Request $request)
    {
        //valida lo que estamos subiendo
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        $url = $request->file('file')->store('products', 'public');//storage
        $product->images()->create([
            'url' => $url
        ]);
    }
}
