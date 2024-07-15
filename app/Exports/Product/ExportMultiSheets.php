<?php

namespace App\Exports\Product;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;


class ExportMultiSheets implements WithMultipleSheets
{
    protected $time;

    public function __construct($time)
    {
        $this->time = $time;
    }

    public function sheets(): array
    {
        $daysInMonth = $this->generateDaysInMonth($this->time);
        $daysInMonthYMD = $this->convertDateFormat($daysInMonth);

        return [
            new FAPVExport($this->time),
            new ProductExport($this->time, $daysInMonth, $daysInMonthYMD),
            new ProductCheck200Export($this->time, $daysInMonth, $daysInMonthYMD),
            new ProductDeliveryExport($this->time, $daysInMonth, $daysInMonthYMD),
            new ProductError($this->time, $daysInMonth, $daysInMonthYMD),
        ];
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
