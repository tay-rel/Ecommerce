<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        //obtenemos todas las categorias para pasarla a la vista welcome
        $categories = Category::all();
        return view('welcome', compact('categories'));
    }
}
