<?php

namespace App\Http\Controllers;

use App\Models\DailyQuantity;
use App\Models\Product;
use App\Models\ProductionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionPlanController extends Controller
{

    public function index(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::now()->format('Y-m'));
        $month = Carbon::createFromFormat('Y-m', $selectedDate)->format('m');
        $year = Carbon::createFromFormat('Y-m', $selectedDate)->format('Y');

        // Lấy danh sách các kế hoạch sản xuất cho tháng và năm được chọn
        $productPlans = ProductionPlan::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        // Lấy danh sách sản phẩm
        $products = Product::all();

        // Trả về view với dữ liệu cần thiết
        return view('productplan.index', compact('productPlans', 'selectedDate', 'month', 'products'));
    }


    public function addProductPlan()
    {
        $products = Product::all();
        $materials = ['WS641-B50', 'EP540N', 'ZS609-N', 'J783-N'];
        $packagingTypes = [
            "20X20", "25X25", "25X35", "35X50", "LÓT LỚN", "LÓT LỚNX2", "LÓT NHỎ"
        ];

        return view('productplan.add-product-plan', compact('products', 'materials', 'packagingTypes'));
    }

    public function storeProductPlan(Request $request)
    {
        DB::beginTransaction();
        try {
            // Lấy dữ liệu từ request
            $productIds = $request->input('product_id');                            // ID sản phẩm
            $materials = $request->input('material_name');                          // Tên nguyên vật liệu
            $packaging = $request->input('packaging_type');                         // Loại bao bì

            // Tạo mới một bản ghi ProductPlan
            $productPlan = new ProductionPlan;
            $productPlan->product_id = $productIds;
            $productPlan->material_name = $materials;                               // Tên nguyên vật liệu
            $productPlan->production_plan = $request->input('production_plan');     // Kế hoạch sản xuất (PCS)

            // Tính giá trị planned_material và cập nhật
            $productionPlan = $request->input('production_plan');                   // Kế hoạch sản xuất (PCS)
            $productDensity = $request->input('product_density');                   // Tỷ trọng sản phẩm (G)
            $plannedMaterial = ($productionPlan * $productDensity) / 1000;
            $productPlan->planned_material = $plannedMaterial;                      // Dự định vật liệu (KG)

            // Lấy số bao bì/thùng và sản phẩm/thùng từ request
            $packagingCountPerBox = $request->input('packaging_count_per_box');     // Số bao bì/thùng
            $productsPerBox = $request->input('products_per_box');                  // Sản phẩm/thùng
            $productPlan->products_per_box = $productsPerBox;

            // Thiết lập các thuộc tính khác
            $productPlan->packaging_type = $packaging;                              // Loại bao bì
            $productPlan->packaging_count_per_box = $packagingCountPerBox;          // Số bao bì/thùng
            $productPlan->box_type = $request->input('box_type');                   // Loại thùng
            $productPlan->product_density = $productDensity;                        // Tỷ trọng sản phẩm (G)
            $productPlan->cavity_count = $request->input('cavity_count');           // Số cavity

            // Tính toán daily_production_plan
            $cycle = $request->input('cycle');                                      // Chu kỳ
            $cavityCount = $request->input('cavity_count');                         // Số cavity
            $dailyProductionPlan = ((22 * 3600) / $cycle) * $cavityCount;
            $productPlan->daily_production_plan = $dailyProductionPlan;             // Kế hoạch sản xuất/ngày

            // Thiết lập các thuộc tính khác
            $productPlan->cycle = $cycle;                                           // Chu kỳ
            $productPlan->ton = $request->input('ton');                             // Tấn
            $productPlan->machine = $request->input('machine');                     // Máy

            // Tính toán box_quantity và total_packaging
            $boxQuantity = $productionPlan / $productsPerBox;
            $productPlan->box_quantity = $boxQuantity;                              // Số lượng thùng

            $totalPackaging = $packagingCountPerBox * $boxQuantity;
            $productPlan->total_packaging = $totalPackaging;                        // Tổng bao bì

            // Tính toán machine_run_days
            $machineRunDays = $productionPlan / $dailyProductionPlan;
            $productPlan->machine_run_days = $machineRunDays;                       // Số ngày chạy máy

            // Lấy dữ liệu từ bảng DailyQuantity với status = 1, product_id tương ứng, và trong tháng hiện tại
            $producedQuantity = DailyQuantity::where('status', 1)
                ->where('product_id', $productIds)
                ->whereMonth('date', now()->month) // Lọc theo tháng hiện tại
                ->whereYear('date', now()->year) // Lọc theo năm hiện tại
                ->sum('quantity'); // Tính tổng số lượng

            $productPlan->produced_quantity = $producedQuantity; // Số Lượng Đã SX (PCS)
            $remainingProductionQuantity = $productionPlan - $producedQuantity;
            $productPlan->remaining_production_quantity = $remainingProductionQuantity; // Số lượng còn sản xuất (PCS)
            $remainingProductionDays = $remainingProductionQuantity > 0 ? $remainingProductionQuantity / $dailyProductionPlan : 0;
            $productPlan->remaining_production_days = $remainingProductionDays; // Số ngày còn sản xuất (ngày)

            // dd($producedQuantity);
            // Lưu bản ghi
            $productPlan->save();
            // $productPlan->remaining_production_days = ($request->remaining_production_days);             // Số ngày còn sản xuất (ngày)
            DB::commit();
            toast('Thêm kế hoạch sản phẩm mới thành công!', 'success', 'top-right');
            return redirect()->route('admin.product-plan.add');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('errors: ' . $e->getMessage() . ' - getLine: ' . $e->getLine());
            toast('Thêm kế hoạch sản phẩm không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }


    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            // Tìm bản ghi cần cập nhật
            $productPlan = ProductionPlan::findOrFail($request->input('id'));

            // Cập nhật các thuộc tính của bản ghi
            $productPlan->product_id = $request->input('product_id');
            $productPlan->material_name = $request->input('material_name');
            $productPlan->production_plan = $request->input('production_plan');

            // Tính giá trị planned_material và cập nhật
            $productionPlan = $request->input('production_plan');
            $productDensity = $request->input('product_density');
            $plannedMaterial = ($productionPlan * $productDensity) / 1000;
            $productPlan->planned_material = $plannedMaterial;

            // Cập nhật các thuộc tính khác
            $productPlan->packaging_type = $request->input('packaging_type');
            $productPlan->packaging_count_per_box = $request->input('packaging_count_per_box');
            $productPlan->box_type = $request->input('box_type');
            $productPlan->product_density = $productDensity;
            $productPlan->cavity_count = $request->input('cavity_count');

            // Tính toán daily_production_plan
            $cycle = $request->input('cycle');
            $cavityCount = $request->input('cavity_count');
            $dailyProductionPlan = ((22 * 3600) / $cycle) * $cavityCount;
            $productPlan->daily_production_plan = $dailyProductionPlan;

            // Cập nhật các thuộc tính khác
            $productPlan->cycle = $cycle;
            $productPlan->ton = $request->input('ton');
            $productPlan->machine = $request->input('machine');

            // Tính toán box_quantity và total_packaging
            $productsPerBox = $request->input('products_per_box');
            $packagingCountPerBox = $request->input('packaging_count_per_box');
            $boxQuantity = $productionPlan / $productsPerBox;
            $productPlan->box_quantity = $boxQuantity;

            $totalPackaging = $packagingCountPerBox * $boxQuantity;
            $productPlan->total_packaging = $totalPackaging;

            // Tính toán machine_run_days
            $machineRunDays = $productionPlan / $dailyProductionPlan;
            $productPlan->machine_run_days = $machineRunDays;

            // Cập nhật số lượng đã sản xuất và còn lại
            $producedQuantity = DailyQuantity::where('status', 1)
                ->where('product_id', $request->input('product_id'))
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('quantity');

            $productPlan->produced_quantity = $producedQuantity;
            $remainingProductionQuantity = $productionPlan - $producedQuantity;
            $productPlan->remaining_production_quantity = $remainingProductionQuantity;
            $remainingProductionDays = $remainingProductionQuantity > 0 ? $remainingProductionQuantity / $dailyProductionPlan : 0;
            $productPlan->remaining_production_days = $remainingProductionDays;

            // Lưu bản ghi
            $productPlan->save();
            DB::commit();

            toast('Cập nhật kế hoạch sản phẩm thành công!', 'success', 'top-right');
            return redirect()->route('admin.product-plan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('errors: ' . $e->getMessage() . ' - getLine: ' . $e->getLine());
            toast('Cập nhật kế hoạch sản phẩm không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
