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

    // Chức Năng Add vs Update (Hàm Chức Năng)
    private function setProductionPlanAttributes($productPlan, $request)
    {
        // Kế hoạch sản xuất (PCS)
        $productionPlan = $request->input('production_plan');
        // Tỷ trọng sản phẩm (G)
        $productDensity = $request->input('product_density');
        // Tính dự định vật liệu (planned_material) (KG)
        $plannedMaterial = ($productionPlan * $productDensity) / 1000;

        // Số bao bì/thùng
        $packagingCountPerBox = $request->input('packaging_count_per_box');
        // Sản phẩm/thùng

        $productsPerBox = $request->input('products_per_box');
        $productPlan->products_per_box = $productsPerBox;
        // Tính số lượng thùng (box_quantity)
        $boxQuantity = $productsPerBox == 0 ? 0 : $productionPlan / $productsPerBox;
        // Tính tổng bao bì (total_packaging)
        $totalPackaging = $packagingCountPerBox * $boxQuantity;

        // Chu kỳ
        $cycle = $request->input('cycle');
        // Số cavity
        $cavityCount = $request->input('cavity_count');
        // Tính kế hoạch sản xuất/ngày (daily_production_plan)
        $dailyProductionPlan = ((22 * 3600) / $cycle) * $cavityCount;

        // Tính số ngày chạy máy (machine_run_days)
        $machineRunDays = $dailyProductionPlan == 0 ? 0 : $productionPlan / $dailyProductionPlan;

        // Lấy số lượng đã sản xuất từ bảng DailyQuantity
        $producedQuantity = DailyQuantity::where('status', 1)
            ->where('product_id', $request->input('product_id'))
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('quantity');

        // Tính số lượng còn sản xuất và số ngày còn sản xuất
        $remainingProductionQuantity = $productionPlan - $producedQuantity;
        $remainingProductionDays = $dailyProductionPlan == 0 || $remainingProductionQuantity == 0 ? 0 : $remainingProductionQuantity / $dailyProductionPlan;

        // Cập nhật các thuộc tính của bản ghi
        // ID sản phẩm
        $productPlan->product_id = $request->input('product_id');
        // Tên nguyên vật liệu
        $productPlan->material_name = $request->input('material_name');
        // Kế hoạch sản xuất (PCS)
        $productPlan->production_plan = $productionPlan;
        // Dự định vật liệu (KG)
        $productPlan->planned_material = $plannedMaterial;
        // Loại bao bì
        $productPlan->packaging_type = $request->input('packaging_type');
        // Số bao bì/thùng
        $productPlan->packaging_count_per_box = $packagingCountPerBox;
        // Loại thùng
        $productPlan->box_type = $request->input('box_type');

        // Tỷ trọng sản phẩm (G)
        $productPlan->product_density = $productDensity;
        // Số cavity
        $productPlan->cavity_count = $cavityCount;
        // Kế hoạch sản xuất/ngày
        $productPlan->daily_production_plan = $dailyProductionPlan;
        // Chu kỳ
        $productPlan->cycle = $cycle;
        // Tấn
        $productPlan->ton = $request->input('ton');
        // Máy
        $productPlan->machine = $request->input('machine');
        // Số lượng thùng
        $productPlan->box_quantity = $boxQuantity;
        // Tổng bao bì
        $productPlan->total_packaging = $totalPackaging;
        // Số ngày chạy máy
        $productPlan->machine_run_days = $machineRunDays;
        // Số Lượng Đã SX (PCS)
        $productPlan->produced_quantity = $producedQuantity;
        // Số lượng còn sản xuất (PCS)
        $productPlan->remaining_production_quantity = $remainingProductionQuantity;
        // Số ngày còn sản xuất (ngày)
        $productPlan->remaining_production_days = $remainingProductionDays;

        return $productPlan;
    }

    // Chức Năng Nguyên Liệu vs Loại Bao Bì (Hàm Chức Năng)
    private function getMaterialsAndPackagingTypes()
    {
        $materials = ['WS641-B50', 'EP540N', 'ZS609-N', 'J783-N'];
        $packagingTypes = [
            "20X20", "25X25", "25X35", "35X50", "LÓT LỚN", "LÓT LỚNX2", "LÓT NHỎ"
        ];
        return compact('materials', 'packagingTypes');
    }

    // View Kế Hoạch Sản Xuất
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
        $materialsAndPackagingTypes = $this->getMaterialsAndPackagingTypes();
        // Trả về view với dữ liệu cần thiết
        return view('productplan.index', array_merge(compact('productPlans', 'selectedDate', 'month', 'products'), $materialsAndPackagingTypes));
    }

    //View Add Kế Hoạch Sản Xuất
    public function addProductPlan()
    {
        $products = Product::all();
        $materialsAndPackagingTypes = $this->getMaterialsAndPackagingTypes();

        return view('productplan.add-product-plan', array_merge(compact('products'), $materialsAndPackagingTypes));
    }

    // Chức Năng Add Kế Hoạch Sản Xuất (Hàm Gọi)
    public function storeProductPlan(Request $request)
    {
        DB::beginTransaction();
        try {
            $productPlan = new ProductionPlan;
            $productPlan = $this->setProductionPlanAttributes($productPlan, $request);
            // dd($productPlan);
            $productPlan->save();

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

    // Chức Năng Update Kế Hoạch Sản Xuất (Hàm Gọi)
    public function updateProductPlan(Request $request)
    {
        DB::beginTransaction();
        try {
            $productPlan = ProductionPlan::findOrFail($request->input('id'));
            $productPlan = $this->setProductionPlanAttributes($productPlan, $request);
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
