<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class Products2Controller extends Controller
{
    public function index()
    {
        return view('admin.products.index');
   }
}
