<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;
use App\Models\Employee;
use Carbon\Carbon;
use App\Models\Celender;
use App\Models\CelenderDetailHNHC;
use App\Models\DailyQuantity;
use Illuminate\Console\Command;
use App\Traits\CalenderTranslate;
use App\Mail\SendMailDailyQuantity as SendMailDailyQuantityMailable;

class SendMailDailyQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-mail-daily-quantity:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Report Quantity';

    /**
     * Execute the console command.
     */
    use CalenderTranslate;

    public function handle()
    {
        $selectedDate = Carbon::yesterday();
        $employees = Employee::whereNotIn('role_id', [15, 16, 17])
            ->where('category_celender_id', '!=', 4)->where('deleted_at', null)
            ->get();

        $calendarIds = Celender::whereYear('date', $selectedDate->year)
            ->whereMonth('date', $selectedDate->month)
            ->pluck('id');

        $calendarDetails = CelenderDetailHNHC::whereIn('employee_id', $employees->pluck('id'))
            ->whereIn('celender_id', $calendarIds)
            ->latest()
            ->get();

        $productivityLogsQuery = DailyQuantity::select(['employee_id', 'date', 'quantity', 'status', 'product_id'])
            ->whereIn('employee_id', $employees->pluck('id'))
            ->whereIn('status', [1, 2, 6])
            ->whereDate('date', $selectedDate)
            ->get();

        $translatedCalendarDetails = [];
        foreach ($calendarDetails as $calendarDetail) {
            $day = $selectedDate->day;
            $column = 'day' . $day;
            if (isset($calendarDetail->$column)) {
                $calendarDetailValue = $this->translateCalendar($calendarDetail->$column);
                $translatedCalendarDetails[$calendarDetail->employee_id] = $calendarDetailValue;
            }
        }
        $loggedInEmployeeIds = DailyQuantity::whereDate('date', $selectedDate->format('Y-m-d'))
            ->distinct()
            ->pluck('employee_id');
        $employeesWithoutProductivity = $employees->reject(function ($employee) use ($loggedInEmployeeIds) {
            return $loggedInEmployeeIds->contains($employee->id);
        });

        Mail::to('locww123vip@gmail.com')
            ->cc(['ctyvinhvinhphat@gmail.com', 'ctyvinhvinhphat1@gmail.com', 'ctyvinhvinhphat1@gmail.com', 'ctyvinhvinhphat5@gmail.com', 'thanhtrieu7272@gmail.com'])
            ->send(new SendMailDailyQuantityMailable($selectedDate, $productivityLogsQuery, $translatedCalendarDetails, $employeesWithoutProductivity));
    }
}
