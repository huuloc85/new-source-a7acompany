<?php

namespace App\Http\Controllers;

use App\Helpers\Status;
use App\Models\Celender;
use App\Models\CelenderDetailHNHC;
use App\Models\CheckEmployee;
use App\Models\Product;
use App\Traits\CalenderTranslate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\DailyQuantity;
use App\Models\Employee;
use App\Models\TotalDailyQuantity;
use App\Models\TotalMonthQuantity;

class CheckEmployeeController extends Controller
{
    use CalenderTranslate;

    //View Admin
    public function index(Request $request)
    {
        $date = $request->input('filter_date', Carbon::today()->toDateString());
        $filterDate = Carbon::parse($date);

        $checkEmployeeHistoryforAdmin = CheckEmployee::with(['employee', 'product'])
            ->whereDate('date', $filterDate)
            ->orderBy('date', 'desc')
            ->get();

        $checkEmployeeHistoryforAdmin->each(function ($checkEmployee) {
            $checkEmployee->date = Carbon::parse($checkEmployee->date);
            $dailyQuantities = DailyQuantity::where('employee_id', $checkEmployee->employee_id)
                ->where('product_id', $checkEmployee->product_id)
                ->whereDate('date', $checkEmployee->date->format('Y-m-d'))
                ->whereIn('status', [1, 2])
                ->get();

            $dailyQuantities->each(function ($dailyQuantity) {
                $dailyQuantity->created_at_formatted = \Carbon\Carbon::parse($dailyQuantity->created_at)->format('H:i:s');
            });

            $checkEmployee->dailyQuantities = $dailyQuantities;
        });

        $products = Product::all();

        return view('checkemployee.view-employee-todo', [
            'checkEmployeeHistoryforAdmin' => $checkEmployeeHistoryforAdmin,
            'products' => $products,
            'filterDate' => $filterDate->toDateString(),
        ]);
    }


    //View Nhân Viên
    public function checkEmployeeTodo(Request $request)
    {
        try {
            $products = Product::get();
            $userId = Auth()->user()->id;
            $calendar = CelenderDetailHNHC::where('employee_id', $userId)->latest()->first();
            $date = Carbon::now()->format('d');
            $date = $this->convertDate($date);
            $column = 'day' . $date;
            $calendarDetail = $calendar->$column;
            $calendarDetail = $this->translateCalendar($calendarDetail);
            $status = Status::getStatusValue(auth()->user()->category_celender->name);
            return view('checkemployee.check-employee-todo', compact('products', 'calendarDetail', 'status'));
        } catch (\Exception $e) {
            toast('Hãy bổ sung lịch làm việc để cập nhật sản lượng!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //Chức năng Nhân Viên
    public function handleCheckEmployeeTodo(Request $request)
    {
        try {
            $employeeId = auth()->user()->id;
            $shift = $request->input('shift');
            $date = Carbon::now();

            // Nếu là ca 2 và giờ hiện tại từ 00:00 đến 08:30, trừ một ngày
            if ($shift == 'Ca 2' && $date->hour >= 0 && $date->hour < 8 && $date->minute <= 30) {
                $date->subDay();
            }

            // Kiểm tra xem bản ghi đã tồn tại chưa
            $existingRecord = CheckEmployee::where('employee_id', $employeeId)
                ->where('product_id', $request->product_id)
                ->where('shift', $shift)
                ->whereDate('date', $date->toDateString())
                ->first();

            if ($existingRecord) {
                toast('Sản phẩm đã tồn tại cho ngày và ca hiện tại!', 'error', 'top-right');
                return redirect()->back();
            }

            $status = Status::getStatusValue(auth()->user()->category_celender->name);
            $checkEmployee = new CheckEmployee();
            $checkEmployee->product_id = $request->product_id;
            $checkEmployee->employee_id = $employeeId;
            $checkEmployee->shift = $shift;
            $checkEmployee->date = $date;
            $checkEmployee->status = $status;
            $checkEmployee->save();

            toast('Cập nhật hoạt động sản phẩm thành công!', 'success', 'top-right');
            return redirect()->back();
        } catch (\Exception $e) {
            toast('Cập nhật hoạt động sản phẩm không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    // chức năng update của nhân viên
    public function updateEmployee(Request $request, $id)
    {
        try {
            $checkEmployee = CheckEmployee::findOrFail($id);
            $currentDate = Carbon::today()->toDateString();
            $monthYear = Carbon::today()->format('m-Y');

            // Kiểm tra sản lượng trong bảng DailyQuantity
            $dailyQuantities = DailyQuantity::where('employee_id', $checkEmployee->employee_id)
                ->where('product_id', $checkEmployee->product_id)
                ->where('status', $checkEmployee->status)
                ->whereDate('date', $currentDate)
                ->get();

            if ($dailyQuantities->isNotEmpty()) {
                // Nếu có sản lượng với cùng status trong cùng một ngày, cho phép chỉnh sửa và xoá sản lượng
                foreach ($dailyQuantities as $dailyQuantity) {
                    // Cập nhật tổng sản lượng trong ngày
                    $totalDaily = TotalDailyQuantity::where('product_id', $dailyQuantity->product_id)
                        ->where('date', $dailyQuantity->date)
                        ->where('status', $dailyQuantity->status)
                        ->first();
                    if ($totalDaily != null) {
                        $totalDaily->totalQuan -= $dailyQuantity->quantity;
                        $totalDaily->save();
                    }

                    // Cập nhật tổng sản lượng trong tháng
                    $totalMonth = TotalMonthQuantity::where('product_id', $dailyQuantity->product_id)
                        ->where('month', $monthYear)
                        ->where('status', $dailyQuantity->status)
                        ->first();
                    if ($totalMonth != null) {
                        $totalMonth->totalQuan = ($totalMonth->totalQuan - $dailyQuantity->quantity);
                        $totalMonth->save();
                    }
                    // Xóa sản lượng trong bảng DailyQuantity
                    $dailyQuantity->delete();
                }
            } else {
                // Nếu không có sản lượng hoặc khác status, kiểm tra ngày có phải ngày hiện tại không
                if ($checkEmployee->created_at->toDateString() != $currentDate) {
                    toast('Quá hạn, không thể thay đổi!', 'error', 'top-right');
                    return redirect()->back();
                }
            }
            // Cập nhật thông tin nhân viên
            $checkEmployee->product_id = $request->product_id;
            $checkEmployee->shift = $request->shift;
            $checkEmployee->status = $request->status;
            $checkEmployee->save();

            toast('Cập nhật thành công!', 'success', 'top-right');
            return redirect()->route('admin.employee-history-check');
        } catch (\Exception $e) {
            toast('Cập nhật không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    //chức năng update của Admin
    public function updateEmployeeforAdmin(Request $request, $id)
    {
        try {
            $checkEmployee = CheckEmployee::findOrFail($id);
            $checkEmployee->product_id = $request->product_id;
            $checkEmployee->shift = $request->shift;
            $checkEmployee->status = $request->status;
            $checkEmployee->save();

            toast('Cập nhật thành công!', 'success', 'top-right');
            return redirect()->route('admin.checkemployee.view-employee-todo');
        } catch (\Exception $e) {
            toast('Cập nhật không thành công!', 'error', 'top-right');
            return redirect()->back();
        }
    }

    // view lịch sử của nhân viên + modal edit
    public function historyEmployeeCheck(Request $request)
    {
        $employeeId = auth()->user()->id;
        $date = $request->input('filter_date', Carbon::today()->toDateString());
        $filterDate = Carbon::parse($date);

        $checkEmployeeHistory = CheckEmployee::where('employee_id', $employeeId)
            ->whereDate('date', $filterDate)
            ->whereIn('status', [1, 2, 6])
            ->orderBy('date', 'desc')
            ->get();

        $products = Product::all();

        $checkEmployeeHistory->each(function ($checkEmployee) {
            // Chuyển đổi date thành đối tượng Carbon nếu chưa phải
            $checkEmployee->date = Carbon::parse($checkEmployee->date);

            $dailyQuantities = DailyQuantity::where('employee_id', $checkEmployee->employee_id)
                ->where('product_id', $checkEmployee->product_id)
                ->whereDate('date', $checkEmployee->date->format('Y-m-d'))
                ->get();

            $dailyQuantities->each(function ($dailyQuantity) {
                $dailyQuantity->updated_at_formatted = Carbon::parse($dailyQuantity->updated_at)->format('H:i:s');
            });

            $checkEmployee->dailyQuantities = $dailyQuantities;
        });

        return view('checkemployee.employee-history-check', [
            'checkEmployeeHistory' => $checkEmployeeHistory,
            'products' => $products,
            'date' => $date,
        ]);
    }


    // delete của nhân viên
    public function deleteHistory($id)
    {
        try {
            $checkEmployee = CheckEmployee::findOrFail($id);
            $checkEmployee->delete();
            toast('Xóa lịch sử thành công', 'success', 'top-right');
            return redirect()->back();
        } catch (\Exception $e) {
            toast('Xóa lịch sử không thành công', 'error', 'top-right');
            return redirect()->back();
        }
    }

    // delete của Admin
    public function deleteCheckEmployee($id)
    {
        try {
            $checkEmployee = CheckEmployee::findOrFail($id);
            $checkEmployee->delete();

            toast('Xóa lịch sử thành công', 'success', 'top-right');
            return redirect()->back();
        } catch (\Exception $e) {
            toast('Xóa lịch sử không thành công', 'error', 'top-right');
            return redirect()->back();
        }
    }
}
