<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    //en request queda almacenada la información que se envio por la url
    public function __invoke(Request $request)
    {
        $products = Product::where('name', 'LIKE' ,"%{$request->name}%")
            ->where('status', 2)
            ->paginate(8);
        return view('search', compact('products'));
    }
}
