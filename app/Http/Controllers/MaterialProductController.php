<?php

namespace App\Http\Controllers;

use App\Models\MaterialProduct;
use App\Models\ProductionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialProductController extends Controller
{
    public function index()
    {
        // Nhóm các MaterialProduct theo name và tính tổng quantity
        $materials = MaterialProduct::select('name', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('name')
            ->get();

        // Truyền dữ liệu đến view
        return view('material.index', compact('materials'));
    }
}
