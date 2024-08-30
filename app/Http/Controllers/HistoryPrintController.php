<?php

namespace App\Http\Controllers;

use App\Models\HistoryPrint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryPrintController extends Controller
{

    public function index(Request $request)
    {
        $query = HistoryPrint::query();

        // Kiểm tra quyền người dùng
        if (Auth::user()->role_id != 15) {
            if (Auth::user()->code != '19010400') {
                // Giới hạn dữ liệu theo employee_id
                $query->where('employee_id', Auth::user()->id);
            }
        }

        // Lọc theo tháng
        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('created_at', Carbon::parse($request->month)->month);
        }

        // Lọc theo type (Tem Thùng hoặc Tem Bịch)
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        $historyprint = $query->get();
        return view('barcode.history', compact('historyprint'));
    }
}
