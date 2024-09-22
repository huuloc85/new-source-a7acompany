<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    //list log
    public function index()
    {
        $logs = Log::orderBy('id', 'DESC')->paginate(Log::paginate);
        $total = Log::count();
        return view('log.index', compact('logs', 'total'));
    }

    //delete
    public function delete($id)
    {
        try {
            $log = Log::find($id);
            $log->delete();
            toast('Xóa log thành công!', 'success', 'top-right');
            return redirect()->route('admin.log');
        } catch (\Exception $e) {
            toast('Xóa log không thành công!', 'error', 'top-right');
            return redirect()->route('admin.log');
        }
    }

    //delete all
    public function deleteAll()
    {
        try {
            Log::truncate();
            toast('Xóa tất cả log thành công!', 'success', 'top-right');
            return redirect()->route('admin.log');
        } catch (\Exception $e) {
            toast('Xóa tất cả log không thành công!', 'error', 'top-right');
            return redirect()->route('admin.log');
        }
    }
}
