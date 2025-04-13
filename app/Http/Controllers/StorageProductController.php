<?php

namespace App\Http\Controllers;

use App\Models\StorageProduct;
use Illuminate\Http\Request;

class StorageProductController extends Controller
{
    public function index(Request $request)
    {
        $storage = StorageProduct::all();

        // Return view with storage products
        return view('storage.product', compact('storage'));
    }
}
