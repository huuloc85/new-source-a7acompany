<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\Product;
use App\Models\StorageProduct;
use App\Models\HistoryPrint;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class StampController extends Controller
{
    //view register barcode
    public function index()
    {
        $products = Product::where('deleted_at', null)->get();
        return view('barcode.add', compact('products'));
    }

    //handle register barcode
    public function barcode(Request $request)
    {
        $products = Product::where('deleted_at', null)->get();
        $product = Product::where('code', $request->code)->first();
        $date = date('d/m/Y', strtotime($request->date));

        //tạo qrCode
        $qrCodeString = $request->code.$request->pcs;
        $qrCode = QrCode::generate($qrCodeString);

        $binCount = $request->binCount;
        $binStart = $request->binStart;
        $generator = new BarcodeGeneratorPNG();

        $binArray = [];
        for ($i = 0; $i < $binCount; $i++) {
            //tạo barcode
            $barcodeString = $product->id."a".str_replace('/', '', $date).$request->shift.sprintf('%03d', $binStart + $i);
            $barcode = base64_encode($generator->getBarcode($barcodeString, $generator::TYPE_CODE_128));
            $data = [
                'bin' => sprintf('%03d', $binStart + $i),
                'barcode' => $barcode
            ];
            array_push($binArray, $data);
        };

        $binArray = $this->mapKeyData($binArray);
        $lotNo = [
            'lot' => 'A',
            'date' => str_replace('/', '', $date),
            'shift' => $request->shift,
            'date_time' => Carbon::now()->format('d/m/Y H:i')
        ];

        return view('barcode.add', compact('qrCode', 'barcode', 'products', 'lotNo', 'binArray', 'product', 'request'));
    }

    //map key data
    public function mapKeyData($binArray) {
        $oddItems = array_filter($binArray, fn($bin) => $bin['bin'] % 2 !== 0);
        $evenItems = array_filter($binArray, fn($bin) => $bin['bin'] % 2 === 0);

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

    //view scan
    public function scan(Request $request)
    {
        return view('barcode.scan');
    }

    //handle check barcode when scan success
    public function checkBarCode(Request $request)
    {
        $data = explode("a", $request->barcode);
        $result = [
            "status" => 500
        ];

        if ($data && count($data) > 1) {
            $productId = $data[0];
            $ltoString = $data[1];
            $date = substr($data[1], 0, 8);
            $shift = substr($data[1], 8, 1);
            $bin = substr($data[1], 9);
            $lot = "A-".$date."-".$shift."-".$bin;
            $result = [
                'barcode' => $request->barcode,
                'date' => $date,
                'shift' => $shift,
                'bin' => $bin,
                'lot' => $lot
            ];

            $product = Product::findOrFail($productId);

            if ($product) {
                //check xem mã đã quét chưa?
                $checkLot = StorageProduct::where('lot', $lot)->first();
                if ($checkLot == null) {
                    $storageProduct = new StorageProduct();
                    $storageProduct->product_id = $productId;
                    $storageProduct->lot = $lot;
                    $storageProduct->save();
                    $result['status'] = 200;
                    return response()->json($result, 200);
                }
                $result['status'] = 400;
                return response()->json($result, 200);
            } else {
                $result['status'] = 404;
                return response()->json($result, 200);
            }
        }

        return response()->json($result, 200);
    }

    //save history print
    public function savePrint(Request $request)
    {
        $product = Product::where('code', $request->productCode)->first();
        if ($product != null) {
            $history = new HistoryPrint();
            $history->product_id = $product->id;
            $history->employee_id = Auth()->user()->id;
            $history->date = $request->date;
            $history->shift = $request->shift;
            $history->binCount = $request->binCount;
            $history->binStart = $request->binStart;
            $history->save();
        }

        return response()->json(200);
    }

    //view packing stamp
    public function packingStamp() {
        $products = Product::where('deleted_at', null)->get();
        return view('packing-stamp.index', compact('products'));
    }

    //handle make packing stamp
    public function StorePackingStamp(Request $request)
    {
        $products = Product::where('deleted_at', null)->get();
        $product = Product::where('code', $request->code)->first();
        $date = date('d/m/Y', strtotime($request->date));
        $binCount = $request->binCount;
        $binStart = $request->binStart;

        $binArray = [];
        for ($i = 0; $i < $binCount; $i++) {
            $data = [
                'bin' => sprintf('%03d', $binStart + $i),
            ];
            array_push($binArray, $data);
        };

        $lotNo = [
            'lot' => 'A',
            'date' => str_replace('/', '', $date),
            'shift' => $request->shift,
            'date_time' => Carbon::now()->format('d/m/Y H:i')
        ];
        
        return view('packing-stamp.index', compact('products', 'lotNo', 'binArray', 'product', 'request'));
    }
}