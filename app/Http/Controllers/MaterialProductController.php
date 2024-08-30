<?php

namespace App\Http\Controllers;

use App\Models\MaterialProduct;
use App\Models\Product;
use App\Models\ProductionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialProductController extends Controller
{
    public function index()
    {
        $materials = Product::whereNotNull('material')
            ->groupBy('material')
            ->pluck('material');

        // Truyền dữ liệu đến view
        return view('material.index', compact('materials'));
    }
}
