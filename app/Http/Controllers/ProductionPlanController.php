<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Product;
use App\Models\ProductionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionPlanController extends Controller
{
    public function index()
    {
        return view('productplan.index');
    }
    public function addProductPlan()
    {
        $products = Product::all();
        $materials = ['WS641-B50', 'EP540N', 'ZS609-N', 'J783-N'];

        return view('productplan.add-product-plan', compact('products', 'materials'));
    }

    public function storeProductPlan(Request $request)
    {
        DB::beginTransaction();
        try {
            // Tạo mới một bản ghi ProductPlan
            $productIds = $request->input('products', []);
            $productPlan = new ProductionPlan;
            $productPlan->product_ids = $productIds;
            $productPlan->material_name = ($request->material_name);                                // Tên nguyên vật liệu
            $productPlan->production_plan = ($request->production_plan);                            // Kế hoạch sản xuất (PCS)
            // $productPlan->planned_material = ($request->planned_material);                          // Dự định vật liệu (KG)
            $productPlan->packaging_type = ($request->packaging_type);                              // Loại bao bì
            $productPlan->packaging_count_per_box = ($request->packaging_count_per_box);            // Số bao bì/thùng
            // $productPlan->total_packaging = ($request->total_packaging);                            // Tổng bao bì
            $productPlan->box_type = ($request->box_type);                                          // Loại thùng
            $productPlan->products_per_box = ($request->products_per_box);                          // Sản phẩm/thùng
            // $productPlan->box_quantity = ($request->box_quantity);                                  // Số lượng thùng
            $productPlan->product_density = ($request->product_density);                            // Tỷ trọng sản phẩm (G)
            // $productPlan->daily_production_plan = ($request->daily_production_plan);                // Kế hoạch sản xuất/ngày
            $productPlan->cavity_count = ($request->cavity_count);                                  // Số cavity
            $productPlan->cycle = ($request->cycle);                                                // Chu kỳ
            $productPlan->ton = ($request->ton);                                                    // Tấn
            $productPlan->machine = ($request->machine);                                            // Máy
            // $productPlan->machine_run_days = ($request->machine_run_days);                          // Số ngày chạy máy
            // $productPlan->remaining_production_days = ($request->remaining_production_days);        // Số ngày còn sản xuất (ngày)
            // $productPlan->remaining_production_quantity = ($request->remaining_production_quantity);// Số lượng còn sản xuất (PCS)
            // $productPlan->produced_quantity = ($request->produced_quantity);                        // Sản lượng đã sản xuất
            // dd($productPlan);
            $productPlan->save();


            DB::commit();
            toast('Thêm kế hoạch sản phẩm mới thành công!', 'success', 'top-right');
            // LogActivity::logRoleSpecificLoginActivity(auth()->user(), 'Admin Thêm Kế Hoạch Sản Phẩm', 'Admin đã thêm kế hoạch sản phẩm');
            return redirect()->route('admin.product-plan.add');
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('errors: ' . $e->getMessage() . ' - getLine: ' . $e->getLine());
            toast('Thêm kế hoạch sản phẩm không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
