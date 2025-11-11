<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StorageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    /**
     * Phân tích barcode thành các thành phần
     * Format: 39a101020251001
     * - 39: product_id
     * - a: ký tự phân cách
     * - 10102025: ngày tháng lot (ddmmyyyy)
     * - 1: ca làm việc
     * - 001: số thứ tự bin
     */
    private function parseBarcode($barcode)
    {
        // Xóa ký tự không phải số hoặc chữ
        $cleanBarcode = preg_replace('/[^a-zA-Z0-9]/', '', $barcode);

        // Tìm vị trí của ký tự chữ cái đầu tiên (separator)
        preg_match('/^(\d+)([a-zA-Z])(\d{8})(\d{1})(\d{3})$/', $cleanBarcode, $matches);

        if (count($matches) !== 6) {
            return null;
        }

        return [
            'product_id' => (int) $matches[1],
            'separator' => $matches[2],
            'date' => $matches[3], // ddmmyyyy
            'shift' => (int) $matches[4],
            'bin_number' => (int) $matches[5],
        ];
    }

    /**
     * Tạo lot code từ date và shift
     */
    private function generateLotCode($date, $shift)
    {
        // Chuyển đổi ddmmyyyy thành định dạng dd/mm/yyyy
        $formattedDate = substr($date, 0, 2).'/'.substr($date, 2, 2).'/'.substr($date, 4, 4);

        return $formattedDate.'/Ca'.$shift;
    }

    /**
     * Scan barcode để tìm hoặc tạo sản phẩm trong kho
     */
    public function scanBarcode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barcode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Phân tích barcode
        $barcodeData = $this->parseBarcode($request->barcode);

        if (! $barcodeData) {
            return response()->json([
                'success' => false,
                'message' => 'Format barcode không đúng. Định dạng: [product_id][separator][ddmmyyyy][shift][bin_number]',
                'example' => '39a101020251001',
            ], 422);
        }

        // Tìm product theo ID
        $product = Product::find($barcodeData['product_id']);
        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => "Không tìm thấy sản phẩm với ID: {$barcodeData['product_id']}",
            ], 404);
        }

        // Tạo lot code
        $lotCode = $this->generateLotCode($barcodeData['date'], $barcodeData['shift']);

        // Tìm hoặc tạo storage product
        $storageProduct = StorageProduct::where([
            'product_id' => $barcodeData['product_id'],
            'lot' => $lotCode,
            'bin' => $barcodeData['bin_number'],
        ])->first();

        if (! $storageProduct) {
            // Tạo mới nếu chưa có
            $storageProduct = StorageProduct::create([
                'product_id' => $barcodeData['product_id'],
                'lot' => $lotCode,
                'bin' => $barcodeData['bin_number'],
                'barcode' => $request->barcode,
                'quantity' => 0,
                'employee_id' => auth()->user()->id,
            ]);
        }

        // Load relationships
        $storageProduct->load(['product', 'employee']);

        // Lấy số lượng mỗi thùng từ product
        $quantityPerBin = $product->quanEntityBin ?? 1;

        return response()->json([
            'success' => true,
            'message' => 'Scan barcode thành công',
            'data' => [
                'storage_product' => [
                    'id' => $storageProduct->id,
                    'product' => $storageProduct->product,
                    'lot' => $storageProduct->lot,
                    'bin' => $storageProduct->bin,
                    'quantity' => $storageProduct->quantity,
                    'barcode' => $storageProduct->barcode,
                ],
                'barcode_info' => [
                    'product_id' => $barcodeData['product_id'],
                    'date' => $barcodeData['date'],
                    'shift' => $barcodeData['shift'],
                    'bin_number' => $barcodeData['bin_number'],
                    'lot_code' => $lotCode,
                ],
                'quantity_per_bin' => $quantityPerBin,
                'total_bins' => $storageProduct->quantity > 0 ? ceil($storageProduct->quantity / $quantityPerBin) : 0,
            ],
        ], 200);
    }

    /**
     * Scan barcode và nhập kho 1 thùng
     */
    public function scanAndStockIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barcode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Phân tích barcode
            $barcodeData = $this->parseBarcode($request->barcode);

            if (! $barcodeData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format barcode không đúng. Định dạng: [product_id][separator][ddmmyyyy][shift][bin_number]',
                    'example' => '39a101020251001',
                ], 422);
            }

            // Tìm product để lấy số lượng mỗi thùng
            $product = Product::find($barcodeData['product_id']);
            if (! $product) {
                return response()->json([
                    'success' => false,
                    'message' => "Không tìm thấy sản phẩm với ID: {$barcodeData['product_id']}",
                ], 404);
            }

            $quantityPerBin = $product->quanEntityBin ?? 1;
            // Mỗi lần scan = 1 thùng
            $totalQuantity = 1 * $quantityPerBin;

            // Tạo lot code
            $lotCode = $this->generateLotCode($barcodeData['date'], $barcodeData['shift']);

            // Kiểm tra xem bin đã tồn tại chưa
            $storageProduct = StorageProduct::where([
                'product_id' => $barcodeData['product_id'],
                'lot' => $lotCode,
                'bin' => $barcodeData['bin_number'],
            ])->first();

            if ($storageProduct) {
                // Bin đã tồn tại, kiểm tra xem đã có sản phẩm chưa
                if ($storageProduct->quantity > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Thùng này đã được nhập kho trước đó',
                        'data' => [
                            'existing_storage' => $storageProduct->load(['product']),
                            'barcode_info' => $barcodeData,
                        ],
                    ], 422);
                }
            } else {
                // Tạo mới storage product
                $storageProduct = StorageProduct::create([
                    'product_id' => $barcodeData['product_id'],
                    'lot' => $lotCode,
                    'bin' => $barcodeData['bin_number'],
                    'barcode' => $request->barcode,
                    'quantity' => 0,
                    'employee_id' => auth()->user()->id,
                ]);
            }

            // Thêm stock vào bin
            $transaction = $storageProduct->addStock(
                $totalQuantity,
                'in',
                null,
                auth()->user()->id
            );

            DB::commit();

            // Load relationships for response
            $transaction->load(['storageProduct.product', 'employee']);

            return response()->json([
                'success' => true,
                'message' => 'Nhập kho thành công',
                'data' => $transaction,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi nhập kho',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get current inventory (all storage products with quantities)
     */
    public function getInventory(Request $request)
    {
        try {
            $query = StorageProduct::with('product')
                ->where('quantity', '>', 0); // Only bins with stock

            // Filter by product_id if provided
            if ($request->filled('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            // Filter by lot if provided
            if ($request->filled('lot')) {
                $query->where('lot', 'like', '%'.$request->lot.'%');
            }

            // Pagination or limit
            $perPage = $request->input('per_page', 50); // Default 50 records
            $storageProducts = $query->orderBy('product_id')
                ->orderBy('lot')
                ->orderBy('bin')
                ->paginate($perPage);

            // Transform data to match frontend expectation
            $transformedData = $storageProducts->getCollection()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Unknown',
                    'lot' => $item->lot,
                    'bin' => $item->bin,
                    'current_quantity' => $item->quantity,
                    'total_quantity' => $item->quantity, // Current = Total for active bins
                    'barcode' => $item->barcode,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

            $storageProducts->setCollection($transformedData);

            return response()->json([
                'success' => true,
                'message' => 'Danh sách tồn kho',
                'data' => $storageProducts,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách tồn kho',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
