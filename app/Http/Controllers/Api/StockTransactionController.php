<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockTransactionStoreRequest;
use App\Http\Requests\StockTransactionUpdateRequest;
use App\Models\Employee;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\StorageProduct;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockTransactionController extends Controller
{
    /**
     * Display a listing of stock transactions
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = StockTransaction::with(['storageProduct.product', 'employee']);

            // Filter by type (in/out)
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            // Filter by storage product
            if ($request->filled('storage_product_id')) {
                $query->where('storage_product_id', $request->storage_product_id);
            }

            // Filter by employee
            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            // Filter by date range
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // Search functionality removed

            // Sort by newest first
            $query->orderBy('created_at', 'desc');

            // Pagination
            $perPage = $request->input('per_page', 15);
            $transactions = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Danh sách giao dịch kho',
                'data' => $transactions,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách giao dịch',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified stock transaction
     */
    public function show(int $id): JsonResponse
    {
        try {
            $transaction = StockTransaction::with(['storageProduct.product', 'employee'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Chi tiết giao dịch kho',
                'data' => $transaction,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Giao dịch không tồn tại',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Store a new stock transaction (Import/Export)
     *
     * @param  StockTransactionStoreRequest  $request
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $storageProduct = StorageProduct::findOrFail($request->storage_product_id);

            // Check if export quantity is available
            if ($request->type === 'out' && $storageProduct->quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng xuất kho vượt quá số lượng hiện có',
                    'data' => [
                        'available_quantity' => $storageProduct->quantity,
                        'requested_quantity' => $request->quantity,
                    ],
                ], 422);
            }

            // Calculate quantity change (đơn giản hóa logic)
            // Trong bin system: mỗi transaction = thay đổi toàn bộ bin

            // Create transaction record
            $transaction = StockTransaction::create([
                'storage_product_id' => $request->storage_product_id,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'employee_id' => $request->employee_id,
            ]);

            // Load relationships trước khi có thể bị xóa
            $storageProduct->load(['product']);

            // Update storage product quantity
            if ($request->type === 'in') {
                $storageProduct->increment('quantity', $request->quantity);
                // Load relationships for response
                $transaction->load(['storageProduct.product', 'employee']);
            } else {
                $storageProduct->decrement('quantity', $request->quantity);

                // Đảm bảo quantity không âm
                if ($storageProduct->quantity < 0) {
                    $storageProduct->quantity = 0;
                    $storageProduct->save();
                }

                // Load relationships cho transaction (không xóa storage_product nữa)
                $transaction->load(['storageProduct.product', 'employee']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $request->type === 'in' ? 'Nhập kho thành công' : 'Xuất kho thành công',
                'data' => $transaction,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo giao dịch kho',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified stock transaction
     * Note: Only allow updating note and employee_id for audit purposes
     *
     * @param  StockTransactionUpdateRequest  $request
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $transaction = StockTransaction::findOrFail($id);

            // Update only allowed fields
            $updateData = [];
            if ($request->has('employee_id')) {
                $updateData['employee_id'] = $request->employee_id;
            }

            if (! empty($updateData)) {
                $transaction->update($updateData);
            }

            // Load relationships for response
            $transaction->load(['storageProduct.product', 'employee']);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giao dịch thành công',
                'data' => $transaction,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật giao dịch',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified stock transaction from storage
     * Note: This should be restricted or log-only for audit purposes
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $transaction = StockTransaction::findOrFail($id);

            // For audit purposes, you might want to soft delete or restrict deletion
            // For now, we'll implement hard delete but recommend adding restrictions

            DB::beginTransaction();

            // Reverse the quantity change in storage_product
            $storageProduct = $transaction->storageProduct;

            if ($storageProduct) {
                if ($transaction->type === 'in') {
                    // If it was import, subtract the quantity
                    $storageProduct->decrement('quantity', $transaction->quantity);
                    // Đảm bảo quantity không âm
                    if ($storageProduct->quantity < 0) {
                        $storageProduct->quantity = 0;
                        $storageProduct->save();
                    }
                } else {
                    // If it was export, add back the quantity
                    $storageProduct->increment('quantity', $transaction->quantity);
                }
            } else {
                // Storage product không tồn tại - không thể reverse
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa transaction này vì storage product không tồn tại',
                ], 422);
            }

            // Delete the transaction
            $transaction->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xóa giao dịch thành công',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa giao dịch',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get stock transaction statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $query = StockTransaction::query();

            // Filter by date range
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            $totalIn = (clone $query)->where('type', 'in')->sum('quantity');
            $totalOut = (clone $query)->where('type', 'out')->sum('quantity');
            $totalTransactions = (clone $query)->count();

            // Get top products by transaction volume
            $limit = $request->input('limit', 50); // Default 50, có thể customize
            $topProducts = StockTransaction::with('storageProduct.product')
                ->select('storage_product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('COUNT(*) as transaction_count'))
                ->when($request->filled('from_date'), function ($q) use ($request) {
                    $q->whereDate('created_at', '>=', $request->from_date);
                })
                ->when($request->filled('to_date'), function ($q) use ($request) {
                    $q->whereDate('created_at', '<=', $request->to_date);
                })
                ->groupBy('storage_product_id')
                ->orderBy('total_quantity', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Thống kê giao dịch kho',
                'data' => [
                    'summary' => [
                        'total_in' => $totalIn,
                        'total_out' => $totalOut,
                        'net_change' => $totalIn - $totalOut,
                        'total_transactions' => $totalTransactions,
                    ],
                    'top_products' => $topProducts,
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thống kê',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get current stock status - All storage products with current quantities
     */
    public function currentStock(Request $request): JsonResponse
    {
        try {
            $query = StorageProduct::with('product');

            // Filter options
            if ($request->filled('product_id')) {
                $query->where('product_id', $request->product_id);
            }

            if ($request->filled('lot')) {
                $query->where('lot', 'like', '%'.$request->lot.'%');
            }

            if ($request->filled('show_empty')) {
                // Include empty bins
                $query->where('quantity', '>=', 0);
            } else {
                // Only bins with stock (default)
                $query->where('quantity', '>', 0);
            }

            $limit = $request->input('limit', 100); // Default 100 records
            $stockData = $query->orderBy('product_id')
                ->orderBy('lot')
                ->orderBy('bin')
                ->limit($limit)
                ->get();

            // Transform to match frontend format
            $transformedData = $stockData->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Unknown Product',
                    'lot' => $item->lot,
                    'bin' => $item->bin,
                    'current_quantity' => $item->quantity,
                    'total_quantity' => $item->quantity, // For compatibility
                    'barcode' => $item->barcode,
                    'employee_id' => $item->employee_id,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Tình trạng tồn kho hiện tại',
                'data' => [
                    'summary' => [
                        'total_bins' => $stockData->count(),
                        'total_products' => $stockData->pluck('product_id')->unique()->count(),
                        'total_quantity' => $stockData->sum('quantity'),
                    ],
                    'top_products' => $transformedData,
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy tình trạng tồn kho',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get transactions by storage product
     */
    public function byStorageProduct(int $storageProductId, Request $request): JsonResponse
    {
        try {
            // Verify storage product exists
            $storageProduct = StorageProduct::with('product')->findOrFail($storageProductId);

            $query = StockTransaction::with(['employee'])
                ->where('storage_product_id', $storageProductId);

            // Filter by type
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            $query->orderBy('created_at', 'desc');

            $perPage = $request->input('per_page', 15);
            $transactions = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Lịch sử giao dịch của sản phẩm',
                'data' => [
                    'storage_product' => $storageProduct,
                    'transactions' => $transactions,
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy lịch sử giao dịch',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Scan barcode để nhập kho đơn giản
     * Tự động sử dụng quanEntityBin làm quantity
     */
    public function scanToStockIn(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
            ]);

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

            // Tìm product theo ID
            $product = Product::find($barcodeData['product_id']);
            if (! $product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm với ID: '.$barcodeData['product_id'],
                ], 404);
            }

            // Tạo lot code và bin code
            $lotCode = $this->generateLotCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['separator']
            );

            $binCode = $this->generateBinCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['bin_number'],
                $barcodeData['separator']
            );

            // Kiểm tra xem bin này đã tồn tại chưa
            $existingStorageProduct = StorageProduct::where('product_id', $barcodeData['product_id'])
                ->where('lot', $lotCode)
                ->where('bin', $barcodeData['bin_number'])
                ->first();

            if ($existingStorageProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thùng này đã được nhập kho trước đó',
                    'data' => [
                        'existing_storage' => $existingStorageProduct,
                        'barcode_info' => $barcodeData,
                    ],
                ], 422);
            }

            // Sử dụng quanEntityBin làm quantity
            $quantity = $product->quanEntityBin ?? 0;

            if ($quantity <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không có quanEntityBin hợp lệ',
                ], 422);
            }

            // Tạo mới storage product
            $storageProduct = StorageProduct::create([
                'product_id' => $barcodeData['product_id'],
                'lot' => $lotCode,
                'bin' => $barcodeData['bin_number'],
                'quantity' => $quantity,
                'barcode' => $request->barcode,
                'employee_id' => auth()->user()->id ?? null,
            ]);

            // Create transaction record (bin system - không cần remaining_quantity)
            $transaction = StockTransaction::create([
                'storage_product_id' => $storageProduct->id,
                'type' => 'in',
                'quantity' => $quantity,
                'employee_id' => auth()->user()->id ?? null,
            ]);

            DB::commit();

            // Load relationships for response
            $transaction->load(['storageProduct.product', 'employee']);

            return response()->json([
                'success' => true,
                'message' => 'Nhập kho thành công',
                'data' => $transaction,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi nhập kho',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Scan barcode để xuất kho đơn giản
     * Tìm bin theo barcode và xuất toàn bộ
     */
    public function scanToStockOut(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
            ]);

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

            // Tạo lot code và bin code để tìm storage product
            $lotCode = $this->generateLotCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['separator']
            );

            $binCode = $this->generateBinCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['bin_number'],
                $barcodeData['separator']
            );

            // Tìm storage product theo bin
            $storageProduct = StorageProduct::where('product_id', $barcodeData['product_id'])
                ->where('lot', $lotCode)
                ->where('bin', $barcodeData['bin_number'])
                ->where('quantity', '>', 0)
                ->first();

            if (! $storageProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thùng hàng này trong kho hoặc đã được xuất',
                    'data' => [
                        'barcode_info' => $barcodeData,
                        'lot_code' => $lotCode,
                        'bin_code' => $binCode,
                    ],
                ], 404);
            }

            // Xuất toàn bộ quantity của thùng
            $quantityToExport = $storageProduct->quantity;

            // Load relationships trước khi xóa
            $storageProduct->load(['product']);

            // Create transaction record (bin system - không cần remaining_quantity)
            $transaction = StockTransaction::create([
                'storage_product_id' => $storageProduct->id,
                'type' => 'out',
                'quantity' => $quantityToExport,
                'employee_id' => auth()->user()->id ?? null,
            ]);

            // Update storage product quantity về 0 (không xóa record)
            $storageProduct->update(['quantity' => 0]);

            // Load relationships for response
            $transaction->load(['storageProduct.product', 'employee']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Xuất kho thành công',
                'data' => $transaction,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xuất kho',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Phân tích barcode thành các thành phần
     * Format: 39a101020251001
     * - 39: product_id
     * - a: ký tự phân cách (lot separator)
     * - 10102025: ngày tháng lot (ddmmyyyy)
     * - 1: ca làm việc
     * - 001: số thứ tự bin (unique bin id)
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
    private function generateLotCode($date, $shift, $separator = 'A')
    {
        // Chuyển đổi ddmmyyyy thành định dạng A-ddmmyyyy-shift-bin
        return strtoupper($separator).'-'.$date.'-'.$shift;
    }

    /**
     * Tạo bin code từ lot và bin number
     */
    private function generateBinCode($date, $shift, $binNumber, $separator = 'A')
    {
        return strtoupper($separator).'-'.$date.'-'.$shift.'-'.str_pad($binNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scan barcode để tìm hoặc tạo sản phẩm trong kho
     */
    public function scanBarcode(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
            ]);

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
                    'message' => 'Không tìm thấy sản phẩm với ID: '.$barcodeData['product_id'],
                ], 404);
            }

            // Tạo lot code và bin code
            $lotCode = $this->generateLotCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['separator']
            );

            $binCode = $this->generateBinCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['bin_number'],
                $barcodeData['separator']
            );

            // Tìm hoặc tạo storage product
            $storageProduct = StorageProduct::where('product_id', $barcodeData['product_id'])
                ->where('lot', $lotCode)
                ->where('bin', $barcodeData['bin_number'])
                ->first();

            if (! $storageProduct) {
                // Tạo mới storage product
                $storageProduct = StorageProduct::create([
                    'product_id' => $barcodeData['product_id'],
                    'lot' => $lotCode,
                    'bin' => $barcodeData['bin_number'],
                    'quantity' => 0,
                    'barcode' => $request->barcode,
                    'employee_id' => $request->input('employee_id', null),
                ]);
            }

            // Load product relationship
            $storageProduct->load('product');

            // Calculate total bins for this lot and product
            $totalBins = StorageProduct::where('product_id', $barcodeData['product_id'])
                ->where('lot', $lotCode)
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'Scan barcode thành công',
                'data' => [
                    'storage_product' => $storageProduct,
                    'barcode_info' => [
                        'product_id' => $barcodeData['product_id'],
                        'date' => $barcodeData['date'],
                        'shift' => $barcodeData['shift'],
                        'bin_number' => $barcodeData['bin_number'],
                        'lot_code' => $lotCode,
                    ],
                    'quantity_per_bin' => $product->quanEntityBin ?? 0,
                    'total_bins' => $totalBins,
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi scan barcode',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Scan barcode và tự động nhập kho
     */
    public function scanAndStockIn(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
                'quantity' => 'nullable|integer|min:1|max:999999',
            ]);

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

            // Tìm product theo ID
            $product = Product::find($barcodeData['product_id']);
            if (! $product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm với ID: '.$barcodeData['product_id'],
                ], 404);
            }

            // Sử dụng quanEntityBin nếu không có quantity được truyền vào
            $quantity = $request->quantity ?? $product->quanEntityBin ?? 0;

            if ($quantity <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng không hợp lệ. Vui lòng nhập số lượng hoặc đảm bảo sản phẩm có quanEntityBin > 0',
                ], 422);
            }

            // Tạo lot code và bin code
            $lotCode = $this->generateLotCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['separator']
            );

            $binCode = $this->generateBinCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['bin_number'],
                $barcodeData['separator']
            );

            // Tìm hoặc tạo storage product
            $storageProduct = StorageProduct::where('product_id', $barcodeData['product_id'])
                ->where('lot', $lotCode)
                ->where('bin', $barcodeData['bin_number'])
                ->first();

            if (! $storageProduct) {
                // Tạo mới storage product
                $storageProduct = StorageProduct::create([
                    'product_id' => $barcodeData['product_id'],
                    'lot' => $lotCode,
                    'bin' => $barcodeData['bin_number'],
                    'quantity' => 0,
                    'barcode' => $request->barcode,
                    'employee_id' => $request->input('employee_id', null),
                ]);
            }

            // Create transaction record (bin system - không cần remaining_quantity)
            $transaction = StockTransaction::create([
                'storage_product_id' => $storageProduct->id,
                'type' => 'in',
                'quantity' => $quantity,
                'employee_id' => auth()->user()->id ?? null,
            ]);

            // Update storage product quantity
            $storageProduct->increment('quantity', $quantity);

            DB::commit();

            // Load relationships for response
            $transaction->load(['storageProduct.product', 'employee']);

            return response()->json([
                'success' => true,
                'message' => 'Scan và nhập kho thành công',
                'data' => $transaction,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi scan và nhập kho',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Scan barcode và tự động xuất kho
     */
    public function scanAndStockOut(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
                'quantity' => 'required|integer|min:1|max:999999',
            ]);

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

            // Tạo lot code và bin code
            $lotCode = $this->generateLotCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['separator']
            );

            $binCode = $this->generateBinCode(
                $barcodeData['date'],
                $barcodeData['shift'],
                $barcodeData['bin_number'],
                $barcodeData['separator']
            );

            // Tìm storage product
            $storageProduct = StorageProduct::where('product_id', $barcodeData['product_id'])
                ->where('lot', $lotCode)
                ->where('bin', $barcodeData['bin_number'])
                ->first();

            if (! $storageProduct) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy sản phẩm trong kho với barcode này',
                    'barcode_data' => $barcodeData,
                ], 404);
            }

            // Check if export quantity is available
            if ($storageProduct->quantity < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng xuất kho vượt quá số lượng hiện có',
                    'data' => [
                        'available_quantity' => $storageProduct->quantity,
                        'requested_quantity' => $request->quantity,
                    ],
                ], 422);
            }

            // Create transaction record (bin system - không cần remaining_quantity)
            $transaction = StockTransaction::create([
                'storage_product_id' => $storageProduct->id,
                'type' => 'out',
                'quantity' => $request->quantity,
                'employee_id' => auth()->user()->id ?? null,
            ]);

            // Load relationships trước khi có thể bị xóa
            $storageProduct->load(['product']);

            // Update storage product quantity
            $storageProduct->decrement('quantity', $request->quantity);

            // Đảm bảo quantity không âm
            if ($storageProduct->quantity < 0) {
                $storageProduct->quantity = 0;
                $storageProduct->save();
            }

            // Load relationships cho transaction (không xóa storage_product nữa)
            $transaction->load(['storageProduct.product', 'employee']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Scan và xuất kho thành công',
                'data' => $transaction,
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi scan và xuất kho',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Scan barcode và tự động nhập kho với quantity = quanEntityBin
     */
    public function scanAndAutoStockIn(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'barcode' => 'required|string',
                'note' => 'nullable|string|max:500',
            ]);

            // Merge quantity = null để sử dụng quanEntityBin
            $request->merge(['quantity' => null]);

            return $this->scanAndStockIn($request);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi scan và nhập kho tự động',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
