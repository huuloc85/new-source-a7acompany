<?php

namespace App\Http\Controllers;

use App\Models\MaterialProduct;

class MaterialProductController extends Controller
{
    public function index()
    {
        // Thực hiện truy vấn để cộng dồn quantity theo material
        $materials = MaterialProduct::with('product')
            ->whereHas('product', function ($query) {
                $query->whereNotNull('material');
            })
            ->get()
            ->groupBy('product.material')
            ->map(function ($group, $material) {
                return [
                    'material' => $material,
                    'total_quantity' => $group->sum('quantity'),
                ];
            })
            ->values();

        // Truyền dữ liệu đến view
        return view('material.index', compact('materials'));
    }
}
