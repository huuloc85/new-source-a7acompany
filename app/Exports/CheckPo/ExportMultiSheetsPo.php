<?php

namespace App\Exports\Checkpo;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportMultiSheetsPo implements WithMultipleSheets
{
    protected $time;
    protected $months;
    protected $delimiter;
    protected $daysInMonth;
    protected $daysInMonthYMD;

    public function __construct($time, $months)
    {
        $this->time = $time;
        $this->months = $months;
    }

    public function sheets(): array
    {
        $daysInMonth = $this->generateDaysInMonth($this->time);
        $daysInMonthYMD = $this->convertDateFormat($daysInMonth);

        $weekNames = ['FAPV Tuần Thứ 1', 'FAPV  Tuần Thứ 2', 'FAPV Tuần Thứ 3', 'FAPV Tuần Thứ 4', 'FAPV Tuần Thứ 5'];
        $sheets = [];

        foreach ($this->months as $i => $weekArray) {
            $sheets[] = new Week($i, $weekArray, $weekNames[$i], $this->time);
        }
        $sheets[] = new Daily($this->time, $daysInMonth, $daysInMonthYMD);
        $sheets[] = new Error($this->time, $daysInMonth, $daysInMonthYMD);
        return $sheets;
    }
    function generateDaysInMonth($monthYear)
    {
        // Chuyển đổi chuỗi tháng-năm thành đối tượng Carbon
        $startDate = Carbon::createFromFormat('m-Y', $monthYear)->startOfMonth();

        // Lấy số ngày trong tháng
        $daysInMonth = $startDate->daysInMonth;

        // Tạo mảng để lưu trữ các ngày trong tháng
        $daysArray = [];

        // Lặp qua từng ngày và thêm vào mảng
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $daysArray[] = $startDate->copy()->addDays($day - 1)->format('d-m-Y');
        }

        return $daysArray;
    }

    function convertDateFormat($dateArray)
    {
        $formattedDateArray = [];

        foreach ($dateArray as $dateString) {
            $parts = explode('-', $dateString);
            $formattedDate = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
            $formattedDateArray[] = $formattedDate;
        }

        return $formattedDateArray;
    }
}
