<?php

namespace App\Http\Controllers;

use App\Events\SendStampEvent;
use App\Models\CelenderDetailHNHC;
use App\Models\Employee;
use App\Models\Product;
use App\Models\SendStamp;
use App\Traits\CalenderTranslate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Picqer\Barcode\BarcodeGeneratorPNG;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SendStampController extends Controller
{
    use CalenderTranslate;

    public function index(Request $request)
    {
        $userId = Auth()->user()->id;
        $calendar = CelenderDetailHNHC::where('employee_id', $userId)->latest()->first();
        $date = Carbon::now()->format('d');
        $date = $this->convertDate($date);
        $column = 'day'.$date;
        $calendarDetail = $calendar ? $calendar->$column : null;
        $calendarDetail = $this->translateCalendar($calendarDetail ?? '');
        $products = Product::all();
        // Lấy bản ghi theo ngày hiện tại hoặc ngày trước nếu là ca 2
        $today = Carbon::now()->toDateString();
        $yesterday = Carbon::now()->subDay()->toDateString(); // Ngày trước

        return view('sendstamp.index', compact('calendarDetail', 'products'));
    }

    public function handleAdd(Request $request)
    {
        DB::beginTransaction();
        try {
            $employeeId = Auth::id();
            $dates = $request->date;
            $productIds = $request->product_id;
            $shifts = $request->shift;
            $binCounts = $request->binCount;
            $binStarts = $request->binStart;
            $types = $request->type;
            $status = $request->status;

            $sendStamps = [];

            foreach ($productIds as $key => $productId) {
                $sendStamps[] = SendStamp::create([
                    'product_id' => $productId,
                    'employee_id' => $employeeId,
                    'date' => Carbon::parse($dates[$key])->toDateString(),
                    'shift' => $shifts[$key],
                    'binCount' => $binCounts[$key],
                    'binStart' => $binStarts[$key],
                    'type' => $types[$key],
                    'status' => $status,
                ]);
            }

            // Lấy danh sách nhân viên có role_id là 15 hoặc 8
            $users = Employee::whereIn('role_id', [15, 8])->whereNull('deleted_at')->get();

            // Phát sự kiện cho từng yêu cầu in tem
            foreach ($sendStamps as $sendStamp) {
                foreach ($users as $user) {
                    event(new SendStampEvent($sendStamp, $user));
                }
            }

            Log::info('SendStampEvent has been broadcasted', ['data' => $sendStamps]);

            DB::commit();
            toast('Gửi yêu cầu in tem thành công!', 'success', 'top-right');

            return redirect()->route('admin.send-stamp');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi: '.$e->getMessage().' - Dòng: '.$e->getLine());
            toast('Gửi yêu cầu in tem không thành công!', 'error', 'top-right');

            return redirect()->back();
        }
    }

    public function checkStamp(Request $request)
    {
        $query = SendStamp::query();

        // Nếu không có ngày được chọn, mặc định lấy ngày hiện tại
        $date = $request->get('date', Carbon::today()->toDateString());
        $query->whereDate('created_at', $date);

        // Lọc theo sản phẩm
        if ($request->filled('product_name')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('name', $request->product_name);
            });
        }

        // Lọc theo nhân viên
        if ($request->filled('employee_name')) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('name', $request->employee_name);
            });
        }

        // Lọc theo ca làm việc
        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lấy danh sách sản phẩm từ dữ liệu đã lọc
        $productIds = $query->pluck('product_id')->unique();
        $products = Product::whereIn('id', $productIds)->get();

        // Lấy danh sách nhân viên từ dữ liệu đã lọc
        $employeeIds = $query->pluck('employee_id')->unique();
        $employees = Employee::whereIn('id', $employeeIds)->get();

        // Lấy danh sách ca và trạng thái từ dữ liệu đã lọc
        $availableShifts = $query->pluck('shift')->unique()->toArray();
        $availableStatuses = $query->pluck('status')->unique()->toArray();

        $historyprint = $query->get();

        return view('checkstamp.index', compact('historyprint', 'products', 'employees', 'availableShifts', 'availableStatuses', 'date'));
    }

    public function print($id)
    {
        $history = SendStamp::findOrFail($id);

        // Kiểm tra nếu type là "Tem Thùng" thì gọi hàm temthung
        if ($history->type === 'Tem Thùng') {
            return $this->temthung($id);
        }

        // Kiểm tra nếu type là "Tem Bịch" thì gọi hàm tembich
        if ($history->type === 'Tem Bịch') {
            return $this->tembich($id);
        }

        return view('checkstamp.index', compact('history'));
    }

    public function temthung($id)
    {
        // Lấy dữ liệu SendStamp từ database
        $sendStamp = SendStamp::findOrFail($id);
        $products = Product::whereNull('deleted_at')->get();
        // Lấy product từ product_id của sendStamp
        $product = Product::find($sendStamp->product_id);

        if (! $product) {
            return back()->with('error', 'Không tìm thấy sản phẩm.');
        }

        $date = date('d/m/Y', strtotime($sendStamp->date));

        // Tạo QRCode - lấy code từ product
        $firstFiveChars = substr($product->code, 0, 5);
        $qrCodeString = $firstFiveChars.'-'.$product->quanEntityBin;
        $qrCode = QrCode::generate($qrCodeString);
        $binCount = $sendStamp->binCount;
        $binStart = $sendStamp->binStart;
        $generator = new BarcodeGeneratorPNG;

        $binArray = [];

        // Kiểm tra nếu binStart không phải là chuỗi hoặc không có dấu phẩy
        if (! is_string($binStart) || strpos($binStart, ',') === false) {
            for ($i = 0; $i < $binCount; $i++) {
                $barcodeString = $product->id.'a'.str_replace('/', '', $date).$sendStamp->shift.sprintf('%03d', $binStart + $i);
                $barcode = base64_encode($generator->getBarcode($barcodeString, $generator::TYPE_CODE_128));
                $data = [
                    'bin' => sprintf('%03d', $binStart + $i),
                    'barcode' => $barcode,
                ];
                array_push($binArray, $data);
            }
        } else {
            $binStartArray = explode(',', $binStart);
            foreach ($binStartArray as $index => $currentBinStart) {
                if ($index >= $binCount) {
                    break;
                }
                if (is_numeric($currentBinStart)) {
                    $currentBinStart = (int) $currentBinStart;
                    $barcodeString = $product->id.'a'.str_replace('/', '', $date).$sendStamp->shift.sprintf('%03d', $currentBinStart);
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
            'shift' => $sendStamp->shift,
            'date_time' => $this->getDateTimeBasedOnShift($sendStamp->shift, $date),
        ];
        // dd($binArray, $lotNo);

        return view('checkstamp.temthung', compact('qrCode', 'products', 'lotNo', 'binArray', 'product', 'sendStamp'));
    }

    public function tembich($id)
    {
        $sendStamp = SendStamp::findOrFail($id); // Lấy dữ liệu từ bảng SendStamp
        $products = Product::whereNull('deleted_at')->get();
        $product = Product::find($sendStamp->product_id);

        // Lấy thông tin từ sendStamp thay vì request
        $date = date('d/m/Y', strtotime($sendStamp->date));
        $binCount = $sendStamp->binCount;
        $binStart = $sendStamp->binStart;

        $binArray = [];

        // Chuyển đổi binStart thành mảng nếu cần
        if (is_string($binStart) && strpos($binStart, ',') !== false) {
            $binStartArray = explode(',', $binStart);
        } else {
            $binStartArray = is_numeric($binStart) ? range($binStart, $binStart + $binCount - 1) : [$binStart];
        }

        // Lặp qua từng giá trị trong binStartArray
        foreach ($binStartArray as $index => $currentBinStart) {
            if ($index >= $binCount) {
                break;
            }

            $data = [
                'bin' => sprintf('%03d', $currentBinStart),
            ];
            array_push($binArray, $data);
        }

        $lotNo = [
            'lot' => 'A',
            'date' => str_replace('/', '', $date),
            'shift' => $sendStamp->shift,
            'date_time' => $this->getDateTimeBasedOnShift($sendStamp->shift, $date),
        ];

        return view('checkstamp.tembich', compact('products', 'lotNo', 'binArray', 'product', 'sendStamp'));
    }

    public function rejectPrint($id)
    {
        try {
            $sendStamp = SendStamp::findOrFail($id);
            $sendStamp->update([
                'status' => 'rejected',
                'manager_id' => auth()->id(),
                'manager_time' => Carbon::now()->format('H:i:s'),
            ]);

            // dd($sendStamp);
            return redirect()->route('admin.checkstamp')->with('success', 'Đã từ chối thành công!');
        } catch (\Exception $e) {
            return redirect()->route('admin.checkstamp')->with('error', 'Có lỗi xảy ra: '.$e->getMessage());
        }
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

    public function savePrint(Request $request)
    {
        $sendStamp = SendStamp::find($request->sendStampId);

        if (! $sendStamp) {
            return response()->json(['error' => 'Không tìm thấy dữ liệu'], 404);
        }

        // Chỉ xử lý nếu trạng thái là 'pending'
        if ($sendStamp->status === 'pending') {
            $product = Product::find($sendStamp->product_id);

            if ($product) {
                $listBin = explode(',', $sendStamp->binStart);

                if (count($listBin) > 1) {
                    foreach ($listBin as $bin) {
                        $newSendStamp = $sendStamp->replicate(); // Sao chép bản ghi gốc
                        $newSendStamp->manager_id = Auth()->user()->id;
                        $newSendStamp->binCount = 1;
                        $newSendStamp->binStart = $bin;
                        $newSendStamp->status = 'approve';
                        $newSendStamp->manager_time = Carbon::now()->format('H:i:s');
                        $newSendStamp->save();
                    }

                    // Xóa bản ghi gốc sau khi tách thành công
                    $sendStamp->delete();
                } else {
                    // Nếu chỉ có một giá trị, cập nhật trực tiếp bản ghi gốc
                    $sendStamp->manager_id = Auth()->user()->id;
                    $sendStamp->status = 'approve';
                    $sendStamp->manager_time = Carbon::now()->format('H:i:s');
                    $sendStamp->save();
                }
            }
        } else {
            return response()->json(['error' => 'Trạng thái không hợp lệ'], 400);
        }

        return response()->json(200);
    }

    public function checkStampEmployee(Request $request)
    {
        $user = auth()->user();
        $date = $request->input('date', now()->toDateString()); // Mặc định lấy ngày hiện tại

        // Chỉ lấy danh sách ngày mà nhân viên đăng nhập có dữ liệu
        $availableDates = SendStamp::where('employee_id', $user->id) // Chỉ lấy của nhân viên hiện tại
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->pluck('date');

        // Lọc dữ liệu theo ngày
        $pendingStamps = SendStamp::where('employee_id', $user->id)
            ->where('status', 'pending')
            ->whereDate('created_at', $date)
            ->get();

        $sendStamps = SendStamp::where('employee_id', $user->id)
            ->whereDate('created_at', $date)
            ->pluck('id');

        $approveStamps = SendStamp::where('employee_id', $user->id)
            ->where('status', 'approve')
            ->whereDate('created_at', $date)
            ->get();

        $rejectedStamps = SendStamp::where('employee_id', $user->id)
            ->where('status', 'rejected')
            ->whereDate('created_at', $date)
            ->get();

        return view('checkstamp.status', compact('pendingStamps', 'approveStamps', 'rejectedStamps', 'date', 'availableDates'));
    }
}
