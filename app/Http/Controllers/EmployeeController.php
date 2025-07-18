<?php

namespace App\Http\Controllers;

use App\Helpers\LogActivity;
use App\Helpers\LogHelper;
use App\Helpers\NumberToWordsHelper;
use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\CategoryCelender;
use App\Models\Celender;
use App\Models\CelenderDetailEatroom;
use App\Models\CelenderDetailHNHC;
use App\Models\CelenderDetailWC;
use App\Models\CelenderDetailWCCleanMen;
use App\Models\CelenderDetailWCCleanWomen;
use App\Models\Employee;
use App\Models\Role;
use App\Models\SalaryManager;
use App\Models\SalaryOfficialA7A;
use App\Models\SalaryOfficialVVP;
use App\Models\SalaryParttime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    // view list
    public function index(Request $request)
    {
        $employees = Employee::query();

        if (! empty($request->role_id)) {
            $employees->NameRole($request);
        }

        if (! empty($request->calendar_category_id)) {
            $employees->NameCate($request);
        }

        if (! empty($request->address)) {
            $employees->Address($request);
        }

        if (! empty($request->home_town)) {
            $employees->HomeTown($request);
        }

        if (! empty($request->phone)) {
            $employees->Phone($request);
        }

        if (! empty($request->CCCD)) {
            $employees->CCCD($request);
        }

        if (! empty($request->id)) {
            $employees->id($request);
        }

        if (! empty($request->name)) {
            $employees->Name($request);
        }

        if (! empty($request->gender)) {
            $employees->Gender($request);
        }
        if (! empty($request->company)) {
            $employees->where('company', 'LIKE', '%'.$request->company.'%');
        }

        $employees = $employees->where('role_id', '!=', 15)
            ->where('role_id', '!=', 17);
        $totalEmployee = $employees->get();
        $limit = $request->limit ?? Employee::paginate;
        $employees = $employees->orderBy('id', 'DESC')->paginate($limit);
        $total = count($totalEmployee);
        $roles = Role::where('id', '!=', 15)
            ->where('id', '!=', 16)
            ->where('id', '!=', 17)->where('id', '!=', 1)->get();
        $categories = CategoryCelender::all();
        $companies = Employee::whereNotNull('company')
            ->where('company', '!=', '')
            ->distinct()
            ->pluck('company');

        return view('employee.index', compact('employees', 'total', 'roles', 'categories', 'companies'));
    }

    // view add
    public function add()
    {
        $roles = Role::where('id', '!=', 15)
            ->where('id', '!=', 16)
            ->where('id', '!=', 17)->get();
        $categories = CategoryCelender::all();

        return view('employee.add', compact('roles', 'categories'));
    }

    // store new employee
    public function store(EmployeeStoreRequest $request)
    {
        Log::info('Bắt đầu tạo nhân sự mới.', ['data' => $request->all()]);

        $employee = new Employee;
        $employee->name = trim($request->name);
        $employee->phone = trim($request->phone);
        $employee->id = trim($request->id);
        $employee->email = trim($request->email);
        $employee->birthday = trim($request->birthday);
        $employee->address = trim($request->address);
        $employee->home_town = trim($request->home_town);
        $employee->CCCD = trim($request->CCCD);
        $employee->role_id = trim($request->role_id);
        $employee->company = trim($request->company);
        $employee->calendar_category_id = trim($request->calendar_category_id);
        $employee->gender = trim($request->gender);
        $employee->marital_status = $request->marital_status;
        $employee->date_joining = trim($request->date_joining);
        $employee->password = bcrypt($request->id);

        $file = $request->photo;
        $file_card = $request->card_photo;

        // Ảnh chân dung
        if ($request->hasFile('photo')) {
            $fileExtension = $file->getClientOriginalName();
            $fileName = time();
            $newFileName = $fileName.'.'.$fileExtension;
            $request->file('photo')->storeAs('public/employee', $newFileName);
            $employee->photo = $newFileName;

            Log::info('Ảnh nhân sự đã được lưu.', ['file' => $newFileName]);
        }

        // Ảnh thẻ
        if ($request->hasFile('card_photo')) {
            $fileExtensionCard = $file_card->getClientOriginalName();
            $fileNameCard = time();
            $newFileNameCard = $fileNameCard.'.'.$fileExtensionCard;
            $request->file('card_photo')->storeAs('public/employee/card', $newFileNameCard);
            $employee->card_photo = $newFileNameCard;

            Log::info('Ảnh thẻ nhân sự đã được lưu.', ['file' => $newFileNameCard]);
        }

        try {
            $employee->save();
            Log::info('Nhân sự được lưu thành công.', ['employee_id' => $employee->id]);

            toast('Thêm nhân sự mới thành công!', 'success', 'top-right');

            return redirect()->route('admin.employee.home');
        } catch (\Exception $th) {
            Log::error('Lỗi khi lưu nhân sự.', [
                'error' => $th->getMessage(),
                'line' => $th->getLine(),
                'file' => $th->getFile(),
            ]);

            $image = 'public/employee/'.$employee->photo;
            $imageCart = 'public/employee/card/'.$employee->card_photo;
            Storage::delete($image);
            Storage::delete($imageCart);

            toast('Thêm nhân sự mới không thành công!', 'error', 'top-right');

            return redirect()->back();
        }
    }

    // view edit
    public function edit($id)
    {
        $employee = Employee::find($id);
        $roles = Role::where('id', '!=', 15)
            ->where('id', '!=', 16)
            ->where('id', '!=', 17)->get();
        $categories = CategoryCelender::all();

        return view('employee.edit', compact('employee', 'roles', 'categories'));
    }

    // update employee
    public function update(EmployeeUpdateRequest $request, $id)
    {
        $employee = Employee::find($id);
        $employee->name = trim($request->name);
        $employee->phone = trim($request->phone);
        $employee->id = trim($request->id);
        $employee->email = trim($request->email);
        $employee->birthday = trim($request->birthday);
        $employee->address = trim($request->address);
        $employee->home_town = trim($request->home_town);
        $employee->CCCD = trim($request->CCCD);
        $employee->role_id = trim($request->role_id);
        $employee->company = trim($request->company);
        $employee->calendar_category_id = trim($request->calendar_category_id);
        $employee->gender = trim($request->gender);
        $employee->marital_status = $request->marital_status;
        $employee->date_joining = trim($request->date_joining);
        $oldImg = $employee->photo;
        $oldImgCard = $employee->card_photo;
        $file = $request->photo;
        $file_card = $request->card_photo;

        // ảnh
        if ($request->hasFile('photo')) {
            $fileExtension = $file->getClientOriginalName();
            $fileName = time(); // Tạo tên file dựa trên thời gian
            $newFileName = $fileName.'.'.$fileExtension; // Tên file mới
            // Lưu file vào thư mục storage/app/public/image với tên mới
            $request->file('photo')->storeAs('public/employee', $newFileName);
            // Gán trường image của đối tượng task với tên mới
            $employee->photo = $newFileName;
        }

        // ảnh thẻ
        if ($request->hasFile('card_photo')) {
            $fileExtensionCard = $file_card->getClientOriginalName();
            $fileNameCard = time(); // Tạo tên file dựa trên thời gian
            $newFileNameCard = $fileNameCard.'.'.$fileExtensionCard; // Tên file mới
            // Lưu file vào thư mục storage/app/public/image với tên mới
            $request->file('card_photo')->storeAs('public/employee/card', $newFileNameCard);
            // Gán trường image của đối tượng task với tên mới
            $employee->card_photo = $newFileNameCard;
        }

        try {

            $employee->save();
            if ($request->hasFile('photo')) {
                $image = 'public/employee/'.$oldImg;
                Storage::delete($image);
            }
            if ($request->hasFile('card_photo')) {
                $imageCard = 'public/employee/card/'.$oldImgCard;
                Storage::delete($imageCard);
            }
            toast('Cập nhật nhân sự thành công!', 'success', 'top-right');

            return redirect()->route('admin.employee.home');
        } catch (\Exception $th) {
            toast('Cập nhật nhân sự không thành công!', 'error', 'top-right');
            if ($request->hasFile('photo')) {
                $image = 'public/employee/'.$newFileName;
                Storage::delete($image);
            }
            if ($request->hasFile('card_photo')) {
                $imageCard = 'public/employee/card/'.$oldImgCard;
                Storage::delete($imageCard);
            }

            return redirect()->back();
        }
    }

    // delete employee
    public function delete($id)
    {
        $employees = Employee::findOrFail($id);

        // Lấy bản ghi mới nhất từ SalaryManager
        $salaryManager = SalaryManager::latest()->first();

        // Nếu không có bản ghi nào trong SalaryManager thì tiếp tục cho xóa
        if (! $salaryManager) {
            toast('Không thể xóa vì không tìm thấy dữ liệu trong Salary Manager!', 'error', 'top-right');

            return redirect()->route('admin.employee.home');
        }

        // Kiểm tra trong bảng SalaryOfficialVVP và SalaryOfficialA7A dựa trên salaries_manager_id từ bản ghi mới nhất
        $salaryOfficialsVVP = SalaryOfficialVVP::where('salaries_manager_id', $salaryManager->id)
            ->where('employee_id', $id)
            ->first();

        $salaryOfficialsA7A = SalaryOfficialA7A::where('salaries_manager_id', $salaryManager->id)
            ->where('employee_id', $id)
            ->first();

        // Nếu nhân sự tồn tại trong một trong hai bảng lương, không cho phép xóa
        if ($salaryOfficialsVVP || $salaryOfficialsA7A) {
            toast('Không thể xóa nhân sự vì đã có dữ liệu lương trong kỳ lương mới nhất!', 'error', 'top-right');

            return redirect()->route('admin.employee.home');
        }

        try {
            $employees->deleted_at = Carbon::now();
            $employees->save();
            toast('Nhân sự đã được đưa vào thùng rác!', 'success', 'top-right');

            return redirect()->route('admin.employee.home');
        } catch (\Exception $th) {
            toast('Đưa nhân sự vào thùng rác không thành công!', 'error', 'top-right');

            return redirect()->route('admin.employee.home');
        }
    }

    // trash
    public function getTrash(Request $request)
    {
        $employees = Employee::onlyTrashed();

        if (! empty($request->role_id)) {
            $employees->NameRole($request);
        }

        if (! empty($request->calendar_category_id)) {
            $employees->NameCate($request);
        }

        if (! empty($request->address)) {
            $employees->Address($request);
        }

        if (! empty($request->home_town)) {
            $employees->HomeTown($request);
        }

        if (! empty($request->phone)) {
            $employees->Phone($request);
        }

        if (! empty($request->CCCD)) {
            $employees->CCCD($request);
        }

        if (! empty($request->id)) {
            $employees->id($request);
        }

        if (! empty($request->name)) {
            $employees->Name($request);
        }

        if (! empty($request->gender)) {
            $employees->Gender($request);
        }

        $total = count($employees->get());
        $employees = $employees->paginate(Employee::paginate);
        $roles = Role::where('id', '!=', 15)
            ->where('id', '!=', 16)
            ->where('id', '!=', 17)->get();
        $categories = CategoryCelender::all();

        return view('employee.trash', compact('employees', 'total', 'roles', 'categories'));
    }

    // restore
    public function restore($id)
    {
        try {
            $employee = Employee::onlyTrashed()->findOrFail($id);
            $employee->deleted_at = null;
            $employee->save();
            toast('Nhân sự được khôi phục thành công thành công!', 'success', 'top-right');

            return redirect()->route('admin.employee.getTrash');
        } catch (\Exception $th) {
            toast('Khôi phục Nhân sự không thành công!', 'error', 'top-right');

            return redirect()->route('admin.employee.getTrash');
        }
    }

    // show celender
    public function celender(Request $request)
    {
        try {
            $celenders = Celender::query();
            if (! empty($request->key)) {
                $celenders->Name($request->key);
            }
            $total = count($celenders->get());
            $celenders = $celenders->orderBy('id', 'DESC')->paginate(Celender::paginate);

            LogActivity::logViewActivity(auth()->user(), 'Xem Lịch Làm Việc', 'Nhân viên xem lịch làm việc');

            return view('employee.celender', compact('celenders', 'total'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xem lịch làm việc.');
        }
    }

    // show celender detail
    public function celenderDetail($id)
    {
        $employeeId = Auth()->user()->id;
        $celenderDetailHNHC = CelenderDetailHNHC::where('celender_id', $id)->where('employee_id', $employeeId)->first();
        $celenderDetailEatroom = CelenderDetailEatroom::where('celender_id', $id)->where('employee_id', $employeeId)->first();
        $celenderDetailWC = CelenderDetailWC::where('celender_id', $id)->where('employee_id', $employeeId)->first();
        $celenderDetailWCCleanWomen = CelenderDetailWCCleanWomen::where('celender_id', $id)->where('employee_id', $employeeId)->first();
        $celenderDetailWCCleanMen = CelenderDetailWCCleanMen::where('celender_id', $id)->where('employee_id', $employeeId)->first();
        $startDate = Celender::where('id', $id)->pluck('date')->first();
        $dates = [];
        $month = date('m', strtotime($startDate));
        $monthNext = $month;
        $date = $startDate;
        while ($monthNext == $month) {
            array_push($dates, $date);
            $date = date('Y/m/d', strtotime('+1 day', strtotime($date)));
            $monthNext = date('m', strtotime($date));
        }
        $formatDate = new Celender;

        return view('employee.celender-detail', compact(
            'celenderDetailHNHC',
            'celenderDetailEatroom',
            'celenderDetailWC',
            'celenderDetailWCCleanWomen',
            'celenderDetailWCCleanMen',
            'id',
            'formatDate',
            'dates'
        ));
    }

    // show salary
    public function salary(Request $request)
    {
        $user = auth()->user();
        $salaryManagers = SalaryManager::query();
        if (! empty($request->key)) {
            $salaryManagers->Name($request);
        }
        // $end_date = Carbon::now()->format('Y-m-d');
        // $salaryManagers = $salaryManagers->where('date_show', '<=', $end_date);
        $total = count($salaryManagers->get());
        $salaryManagers = $salaryManagers->orderBy('id', 'DESC')->paginate(SalaryManager::paginate);
        LogActivity::logViewActivity(auth()->user(), 'Xem Bảng Lương', 'Nhân viên xem bảng lương');

        return view('employee.salary', compact('salaryManagers', 'total'));
    }

    // show salary
    public function salaryDetail($id)
    {
        try {
            $employee_id = Auth()->user()->id;
            $salaryManager = SalaryManager::find($id);
            $salaryOfficialsVVP = SalaryOfficialVVP::where('salaries_manager_id', $id)->where('employee_id', $employee_id)->first();
            $salaryOfficialsA7A = SalaryOfficialA7A::where('salaries_manager_id', $id)->where('employee_id', $employee_id)->first();
            $salaryParttimes = SalaryParttime::where('salaries_manager_id', $id)->where('employee_id', $employee_id)->first();
            $actuallyReceived = $salaryOfficialsVVP->actually_received ?? $salaryOfficialsA7A->actually_received ?? $salaryParttimes->actually_received ?? 0;
            $salaryInWords = NumberToWordsHelper::convert($actuallyReceived);

            return view('employee.salary-detail', compact('salaryOfficialsVVP', 'salaryOfficialsA7A', 'salaryParttimes', 'salaryManager', 'salaryInWords'));
        } catch (\Exception $e) {
            LogHelper::saveLog('Xem chi tiết bảng lương', $e->getMessage(), $e->getLine());
            toast('Xem chi tiết bảng lương không thành công!', 'error', 'top-right');

            return redirect()->back();
        }
    }
}
