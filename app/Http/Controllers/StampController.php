<?php

namespace App\Http\Controllers;

use App\Events\BarcodeScanned;
use App\Events\QrScanned;
use App\Models\Product;
use App\Models\SendStamp;
use App\Models\StorageProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StampController extends Controller
{
    // view register barcode
    public function index()
    {
        $products = Product::where('deleted_at', null)->get();

        return view('barcode.add', compact('products'));
    }

    // handle register barcode
    public function barcode(Request $request)
    {
        $products = Product::where('deleted_at', null)->get();
        $product = Product::where('code', $request->code)->first();
        $date = date('d/m/Y', strtotime($request->date));

        // Tạo QRCode
        $firstFiveChars = substr($request->code, 0, 5);
        $qrCodeString = $firstFiveChars.'-'.$request->pcs;
        $qrCode = QrCode::generate($qrCodeString);

        $binCount = $request->binCount;
        $binStart = $request->binStart;
        $generator = new BarcodeGeneratorPNG;

        $binArray = [];

        // Kiểm tra nếu binStart không phải là chuỗi hoặc không có dấu phẩy
        if (! is_string($binStart) || strpos($binStart, ',') === false) {
            // Nếu không phải chuỗi hoặc không có dấu phẩy, thực hiện theo cách này
            for ($i = 0; $i < $binCount; $i++) {
                // Tạo barcode
                $barcodeString = $product->id.'a'.str_replace('/', '', $date).$request->shift.sprintf('%03d', $binStart + $i);
                $barcode = base64_encode($generator->getBarcode($barcodeString, $generator::TYPE_CODE_128));
                $data = [
                    'bin' => sprintf('%03d', $binStart + $i),
                    'barcode' => $barcode,
                ];
                array_push($binArray, $data);
            }
        } else {
            // Nếu là chuỗi có dấu phẩy, xử lý như trước
            $binStartArray = explode(',', $binStart); // Tách chuỗi thành mảng
            foreach ($binStartArray as $index => $currentBinStart) {
                if ($index >= $binCount) {
                    break; // Dừng khi đã đủ số lượng binCount
                }
                if (is_numeric($currentBinStart)) {
                    $currentBinStart = (int) $currentBinStart; // Chuyển đổi thành số nguyên
                    // Tạo barcode cho từng giá trị binStart
                    $barcodeString = $product->id.'a'.str_replace('/', '', $date).$request->shift.sprintf('%03d', $currentBinStart);
                    $barcode = base64_encode($generator->getBarcode($barcodeString, $generator::TYPE_CODE_128));

                    $data = [
                        'bin' => sprintf('%03d', $currentBinStart),
                        'barcode' => $barcode,
                    ];
                    array_push($binArray, $data);
                }
            }
        }

        $binArray = $this->mapKeyData($binArray);

        $lotNo = [
            'lot' => 'A',
            'date' => str_replace('/', '', $date),
            'shift' => $request->shift,
            'date_time' => $this->getDateTimeBasedOnShift($request->shift, $date),
        ];

        return view('barcode.add', compact('qrCode', 'barcode', 'products', 'lotNo', 'binArray', 'product', 'request'));
    }

    // format date time
    private function getDateTimeBasedOnShift($shift, $date)
    {
        // Xử lý date để có định dạng chuẩn
        $formattedDate = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');

        // Cài đặt thời gian mặc định dựa trên shift
        $time = ($shift == 1) ? '07:30' : '19:30';

        // Kết hợp ngày và giờ
        return Carbon::createFromFormat('Y-m-d H:i', $formattedDate.' '.$time)->format('d/m/Y H:i');
    }

    // map key data
    public function mapKeyData($binArray)
    {
        $oddItems = array_filter($binArray, fn ($bin) => $bin['bin'] % 2 !== 0);
        $evenItems = array_filter($binArray, fn ($bin) => $bin['bin'] % 2 === 0);

        $rows = [];

        while ($oddItems || $evenItems) {
            $rowOdds = array_splice($oddItems, 0, 3);
            $rowEvens = array_splice($evenItems, 0, 3);

            $rowOdds = array_pad($rowOdds, 3, ['bin' => 'xxxx']);
            $rowEvens = array_pad($rowEvens, 3, ['bin' => 'xxxx']);

            $rows[] = array_merge($rowOdds, $rowEvens);
        }

        $binArray = array_merge(...$rows);

        return $binArray;
    }

    // view scan
    public function scan(Request $request)
    {
        $products = Product::select('id', 'name', 'code')->get();

        return view('barcode.scan', compact('products'));
    }

    // handle check qr code
    public function checkQr(Request $request)
    {
        $qrCode = $request->input('qr_code');

        if (! $qrCode) {
            $data = [
                'status' => 400,
            ];

            broadcast(new QrScanned($data));

            return response()->json($data, 400);
        }

        $product = Product::where('code', $qrCode)->first();

        if (! $product) {
            $data = [
                'status' => 404,
            ];

            broadcast(new QrScanned($data));

            return response()->json($data, 404);
        }

        $data = [
            'status' => 200,
            'id' => $product->id,
            'name' => $product->name,
        ];

        broadcast(new QrScanned($data));

        return response()->json([
            'status' => 200,
            'product_id' => $product->id,
            'product_name' => $product->name,
        ], 200);
    }

    public function checkBarCode(Request $request)
    {
        $employeeId = auth()->user()->id;
        $barcode = $request->barcode;
        $data = explode('a', $barcode);

        if (! $barcode || count($data) < 2) {
            broadcast(new BarcodeScanned([
                'id' => null,
                'name' => 'Không hợp lệ',
                'status' => 422,
            ]));

            return response()->json([
                'status' => 422,
                'product_id' => null,
                'product_name' => null,
            ], 422);
        }

        $productId = $data[0];
        $date = substr($data[1], 0, 8);
        $shift = substr($data[1], 8, 1);
        $bin = substr($data[1], 9);
        $lot = 'A-'.$date.'-'.$shift.'-'.$bin;

        $product = Product::find($productId);

        if (! $product) {
            broadcast(new BarcodeScanned([
                'id' => $productId,
                'name' => 'Không rõ',
                'status' => 404,
            ]));

            return response()->json([
                'status' => 404,
                'product_id' => $productId,
                'product_name' => null,
            ], 404);
        }

        $checkLot = StorageProduct::where('lot', $lot)->first();

        if ($checkLot) {
            broadcast(new BarcodeScanned([
                'id' => $productId,
                'name' => $product->name,
                'status' => 409,
                'lot' => $lot,
            ]));

            return response()->json([
                'status' => 409,
                'product_id' => $product->id,
                'product_name' => $product->name,
            ], 409);
        }

        // Lưu sản phẩm mới
        $storageProduct = new StorageProduct;
        $storageProduct->product_id = $productId;
        $storageProduct->lot = $lot;
        $storageProduct->employee_id = $employeeId;
        $storageProduct->bin = $bin;
        $storageProduct->save();

        broadcast(new BarcodeScanned([
            'id' => $productId,
            'name' => $product->name,
            'status' => 200,
            'lot' => $lot,
        ]));

        return response()->json([
            'status' => 200,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'lot' => $lot,
        ], 200);
    }

    // handle check qr code when scan success

    public function savePrint(Request $request)
    {
        try {
            Log::info('Dữ liệu nhận được:', $request->all()); // Log request để kiểm tra dữ liệu đầu vào

            $product = Product::where('code', $request->productCode)->first();
            if (! $product) {
                Log::error('Sản phẩm không tồn tại: '.$request->productCode);

                return response()->json(['error' => 'Sản phẩm không tồn tại'], 400);
            }

            $listBin = explode(',', $request->binStart);
            if (count($listBin) > 1) {
                foreach ($listBin as $bin) {
                    $history = new SendStamp;
                    $history->product_id = $product->id;
                    $history->manager_id = Auth()->user()->id;
                    $history->employee_id = Auth()->user()->id;
                    $history->type = $request->type;
                    $history->date = $request->date;
                    $history->shift = $request->shift;
                    $history->binCount = 1;
                    $history->binStart = $bin;
                    $history->manager_time = Carbon::now()->format('H:i:s');
                    $history->status = 'approve';
                    $history->save();
                }
            } else {
                $history = new SendStamp;
                $history->product_id = $product->id;
                $history->manager_id = Auth()->user()->id;
                $history->employee_id = Auth()->user()->id;
                $history->type = $request->type;
                $history->date = $request->date;
                $history->shift = $request->shift;
                $history->binCount = $request->binCount;
                $history->binStart = $request->binStart;
                $history->manager_time = Carbon::now()->format('H:i:s');
                $history->status = 'approve';
                $history->save();
            }

            return response()->json(['status' => 200]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi lưu lịch sử print: '.$e->getMessage().' - Dòng: '.$e->getLine());

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // view packing stamp
    public function packingStamp()
    {
        $products = Product::where('deleted_at', null)->get();

        return view('packing-stamp.index', compact('products'));
    }

    // handle make packing stamp
    public function StorePackingStamp(Request $request)
    {
        $products = Product::where('deleted_at', null)->get();
        $product = Product::where('code', $request->code)->first();
        $date = date('d/m/Y', strtotime($request->date));
        $binCount = $request->binCount;
        $binStart = $request->binStart;

        $binArray = [];

        // Kiểm tra nếu binStart là chuỗi có dạng số thập phân phân cách bằng dấu phẩy
        if (is_string($binStart) && strpos($binStart, ',') !== false) {
            $binStartArray = explode(',', $binStart); // Tách chuỗi thành mảng
        } else {
            // Nếu binStart không phải chuỗi có dấu phẩy, chuyển đổi thành mảng có một giá trị
            $binStartArray = is_numeric($binStart) ? range($binStart, $binStart + $binCount - 1) : [$binStart];
        }

        // Lặp qua từng giá trị trong binStartArray
        foreach ($binStartArray as $index => $currentBinStart) {
            if ($index >= $binCount) {
                break; // Dừng khi đã đủ số lượng binCount
            }

            $data = [
                'bin' => sprintf('%03d', $currentBinStart),
            ];
            array_push($binArray, $data);
        }

        $lotNo = [
            'lot' => 'A',
            'date' => str_replace('/', '', $date),
            'shift' => $request->shift,
            'date_time' => $this->getDateTimeBasedOnShift($request->shift, $date),
        ];

        return view('packing-stamp.index', compact('products', 'lotNo', 'binArray', 'product', 'request'));
    }
}
