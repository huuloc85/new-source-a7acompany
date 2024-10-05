<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\TotalMonthQuantity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UpdateStockQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-stock-quantity:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update stock quantity monthly';

    /**
     * Execute the console command.
     */
    public function handle(Request $request)
    {
        try {
            DB::beginTransaction();
            $previousMonth = Carbon::now()->subMonth()->startOfMonth();
            $listProductId = Product::all()->pluck('id');
            $month = Carbon::now()->format('m-Y');
            $count = 0;
            $count200 = 0;
            $countMOQ = 0;
            foreach ($listProductId as $productId) {
                $stockQuan200 = TotalMonthQuantity::where('product_id', $productId)->where('month', $month)->where('status', 5)->first();
                $stockQuan = TotalMonthQuantity::where('product_id', $productId)->where('month', $month)->where('status', 4)->first();
                $stockMOQ = TotalMonthQuantity::where('product_id', $productId)->where('month', $month)->where('status', 7)->first();
                // dd($stockQuan200);
                if ($stockQuan200 == null) {
                    //thực hiện tính lại tồn đầu kỳ 200%
                    $lastStockQuan200 = TotalMonthQuantity::where('product_id', $productId)
                        ->where('status', 5)
                        ->whereMonth('created_at', $previousMonth->month)
                        ->whereYear('created_at', $previousMonth->year)
                        ->value('totalQuan');

                    $laseTotalChecked = TotalMonthQuantity::where('product_id', $productId)
                        ->where('status', 2)
                        ->whereMonth('created_at', $previousMonth->month)
                        ->whereYear('created_at', $previousMonth->year)
                        ->value('totalQuan');

                    $laseTotalExport = TotalMonthQuantity::where('product_id', $productId)
                        ->where('status', 3)
                        ->whereMonth('created_at', $previousMonth->month)
                        ->whereYear('created_at', $previousMonth->year)
                        ->value('totalQuan');
                    // dd($laseTotalChecked, $laseTotalChecked, $laseTotalExport);
                    $newStockQuan200 = new TotalMonthQuantity();
                    $newStockQuan200->product_id = $productId;
                    $newStockQuan200->status = 5;
                    $newStockQuan200->month = $month;
                    $newStockQuan200->totalQuan = ($lastStockQuan200 + $laseTotalChecked) - $laseTotalExport;
                    // dd($newStockQuan200);
                    $newStockQuan200->save();
                    $count200++;
                }

                if ($stockQuan == null) {
                    //thực hiện tính lại tồn đầu kỳ
                    $oldStockQuan = TotalMonthQuantity::where('product_id', $productId)
                        ->where('status', 4)  // tồn đầu kỳ
                        ->whereMonth('created_at', $previousMonth->month)
                        ->whereYear('created_at', $previousMonth->year)
                        ->value('totalQuan');

                    $prorealityQuan = TotalMonthQuantity::where('product_id', $productId)
                        ->where('status', 1)  // sản xuất gần nhất
                        ->whereMonth('created_at', $previousMonth->month)
                        ->whereYear('created_at', $previousMonth->year)
                        ->value('totalQuan');

                    $laseTotalExport = TotalMonthQuantity::where('product_id', $productId)
                        ->where('status', 3)  // xuất tháng gần nhất
                        ->whereMonth('created_at', $previousMonth->month)
                        ->whereYear('created_at', $previousMonth->year)
                        ->value('totalQuan');
                    $stockEndQuan = ($oldStockQuan + $prorealityQuan) - $laseTotalExport; //tồn cuối kỳ gần nhất

                    $newStockQuan = new TotalMonthQuantity();
                    $newStockQuan->product_id = $productId;
                    $newStockQuan->status = 4;
                    $newStockQuan->month = $month;
                    $newStockQuan->totalQuan = $stockEndQuan;
                    $newStockQuan->save();
                    $count++;
                }

                if ($stockMOQ == null) {
                    $stockMOQ = TotalMonthQuantity::where('product_id', $productId)->where('status', 7)->latest()->value('totalQuan');

                    //thực hiện tính lại tồn đầu kỳ
                    $newStockMOQ = new TotalMonthQuantity();
                    $newStockMOQ->product_id = $productId;
                    $newStockMOQ->status = 7;
                    $newStockMOQ->month = $month;
                    $newStockMOQ->totalQuan = $stockMOQ;
                    $newStockMOQ->save();
                    $countMOQ++;
                }
            }
            DB::commit();
            Log::info("Đã cập nhật tồn đầu kỳ cho " . $count . " sản phẩm, tồn đầu kỳ 200% cho " . $count200 . " sản phẩm, MOQ cho " . $countMOQ . "sản phẩm.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('errors' . $e->getMessage() . ' getLine' . $e->getLine());
        }
    }
}
