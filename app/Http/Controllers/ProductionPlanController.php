<?php

namespace App\Http\Controllers;

use App\Models\DailyQuantity;
use App\Models\Product;
use App\Models\ProductionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionPlanController extends Controller
{

    // Chức Năng Add vs Update (Hàm Chức Năng)
    private function setProductionPlanAttributes($productPlan, $request)
    {
        $currenmonth = Carbon::now()->format('m-Y');
        $productPlan->month = $currenmonth;
        // Kế hoạch sản xuất (PCS)
        $productionPlan = $request->input('production_plan');
        // Tỷ trọng sản phẩm (G)
        $productDensity = $request->input('product_density');
        // Tính dự định vật liệu (planned_material) (KG)
        $plannedMaterial = ($productionPlan * $productDensity) / 1000;

        // Số bao bì/thùng
        $packagingCountPerBox = $request->input('packaging_count_per_box');
        // Sản phẩm/thùng

        // Tính số lượng thùng (box_quantity)
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

    // // View Kế Hoạch Sản Xuất
    public function index(Request $request)
    {
        // Lấy tháng hiện tại và tháng được chọn từ yêu cầu
        $selectedMonth = $request->input('month', Carbon::now()->format('m-Y'));
        $currentMonth = Carbon::now()->format('m-Y');

        // Kiểm tra nếu hôm nay là ngày đầu tháng
        $today = Carbon::now();
        if ($today->day === 1) {
            // Kiểm tra nếu đã có dữ liệu cho tháng hiện tại
            $dataExistsForCurrentMonth = ProductionPlan::where('month', $currentMonth)->exists();

            // Nếu chưa có dữ liệu cho tháng hiện tại, sao chép dữ liệu từ tháng trước
            if (!$dataExistsForCurrentMonth) {
                $lastMonth = $today->subMonth()->format('m-Y');

                // Lấy dữ liệu kế hoạch sản xuất cho tháng trước
                $previousMonthPlans = ProductionPlan::where('month', $lastMonth)->get();

                foreach ($previousMonthPlans as $plan) {
                    // Tạo bản ghi mới với dữ liệu của tháng trước và cập nhật tháng mới
                    $newPlan = $plan->replicate();
                    $newPlan->month = $currentMonth;
                    $newPlan->save();
                }
            }
        }

        // Tính toán và cập nhật các giá trị liên quan đến $producedQuantity
        $plans = ProductionPlan::where('month', $selectedMonth)->get();

        foreach ($plans as $plan) {
            // Lấy số lượng đã sản xuất từ bảng DailyQuantity cho tháng hiện tại
            $producedQuantity = DailyQuantity::where('status', 1)
                ->where('product_id', $plan->product_id)
                ->whereMonth('date', Carbon::createFromFormat('m-Y', $selectedMonth)->month)
                ->whereYear('date', Carbon::createFromFormat('m-Y', $selectedMonth)->year)
                ->sum('quantity');

            $remainingProductionQuantity = $plan->production_plan - $producedQuantity;
            $remainingProductionDays = $plan->daily_production_plan == 0 || $remainingProductionQuantity == 0 ? 0 : $remainingProductionQuantity / $plan->daily_production_plan;

            // Cập nhật lại các thuộc tính của bản ghi
            $plan->produced_quantity = $producedQuantity;
            $plan->remaining_production_quantity = $remainingProductionQuantity;
            $plan->remaining_production_days = $remainingProductionDays;
            $plan->save();
        }

        // Lấy danh sách các tháng có trong bản ghi để sử dụng cho bộ lọc
        $months = ProductionPlan::select('month')->distinct()->pluck('month');

        // Lấy dữ liệu kế hoạch sản xuất cho tháng được chọn
        $productPlans = ProductionPlan::where('month', $selectedMonth)->get();

        // Lấy dữ liệu sản phẩm và nguyên vật liệu
        $products = Product::all();
        $materialsAndPackagingTypes = $this->getMaterialsAndPackagingTypes();

        return view('productplan.index', array_merge(compact('productPlans', 'products', 'months', 'selectedMonth', 'currentMonth'), $materialsAndPackagingTypes));
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
            // Kiểm tra sản phẩm đã tồn tại
            $existingPlan = ProductionPlan::where('product_id', $request->input('product_id'))
                ->whereMonth('created_at', date('m')) // Kiểm tra nếu là tháng hiện tại
                ->first();

            if ($existingPlan) {
                toast('Sản phẩm đã có kế hoạch sản xuất trong tháng này!', 'error', 'top-right');
                return redirect()->back();
            }

            // Nếu không có kế hoạch sản phẩm trong tháng hiện tại, tiếp tục thêm kế hoạch mới
            $productPlan = new ProductionPlan;
            $productPlan = $this->setProductionPlanAttributes($productPlan, $request);
            $productPlan->save();

            DB::commit();
            toast('Thêm kế hoạch sản phẩm mới thành công!', 'success', 'top-right');
            return redirect()->route('admin.product-plan.index');
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
            // Tìm kế hoạch sản phẩm bằng ID
            $productPlan = ProductionPlan::findOrFail($request->input('id'));
            $productPlan = $this->setProductionPlanAttributes($productPlan, $request);
            $productPlan->save();

            DB::commit();
            toast('Cập nhật kế hoạch sản phẩm thành công!', 'success', 'top-right');
            return redirect()->route('admin.product-plan.index', ['id' => $productPlan->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('errors: ' . $e->getMessage() . ' - getLine: ' . $e->getLine());
            toast('Cập nhật kế hoạch sản phẩm không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    // Chức Năng Xoá Kế Hoạch Sản Xuất
    public function deleteProductPlan($id)
    {
        DB::beginTransaction();
        try {
            $productPlan = ProductionPlan::findOrFail($id);
            $productPlan->delete();

            DB::commit();
            toast('Xóa kế hoạch sản phẩm thành công!', 'success', 'top-right');
            return redirect()->route('admin.product-plan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('errors: ' . $e->getMessage() . ' - getLine: ' . $e->getLine());
            toast('Xóa kế hoạch sản phẩm không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    // Chức Năng Thêm Số Lượng Cho Kế Hoạch Sản Xuất
    public function configProductPlan()
    {
        // Lấy tất cả kế hoạch sản xuất trong tháng hiện tại
        $productionPlans = ProductionPlan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        // Lấy danh sách sản phẩm để hiển thị trong dropdown
        $products = Product::all();

        // Trả về view chỉnh sửa với dữ liệu
        return view('productplan.config-product-plan', [
            'productionPlans' => $productionPlans,
            'products' => $products,
        ]);
    }

    // Xử lý cập nhật kế hoạch sản xuất
    public function handleConfigProductPlan(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $plansData = $request->input('plans');

            // Kiểm tra nếu mảng plans không rỗng
            if (is_array($plansData)) {
                foreach ($plansData as $planData) {
                    // Kiểm tra nếu các key cần thiết tồn tại trong dữ liệu
                    if (isset($planData['id']) && isset($planData['production_plan'])) {
                        // Tìm kế hoạch sản xuất theo ID
                        $productionPlan = ProductionPlan::findOrFail($planData['id']);

                        // Cập nhật các thuộc tính kế hoạch sản xuất
                        $productionPlan->updateProductionPlanAttributes($planData);
                    }
                }
            }
            toast('Cập nhật kế hoạch sản xuất thành công!', 'success', 'top-right');
            return redirect()->route('admin.product-plan.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('errors: ' . $e->getMessage() . ' - getLine: ' . $e->getLine());
            toast('Cập nhật kế hoạch sản xuất không thành công!', 'error', 'top-right');
            return redirect()->route('admin.product-plan.index');
        }
    }
}
