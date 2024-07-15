<?php

namespace App\Traits;

trait CalenderTranslate
{
    public function convertDate($date)
    {
        for ($i = 1; $i < 10; $i++) {
            $temp = '0' . $i;
            if ($date == $temp) {
                $date = $i;
                return $date;
            }
        }
        return $date;
    }

    public function translateCalendar($calendar)
    {
        $result = "";
        switch ($calendar) {
            case 'N':
                $result = 'Ca 1';
                break;
            case 'D':
                $result = 'Ca 2';
                break;
            case 'X':
                $result = 'Nghỉ';
                break;
            case 'TC':
                $result = 'Tăng cường đêm';
                break;
            case 'LN':
                $result = 'Làm thêm ca ngày';
                break;
            default:
                $result = 'Không xác định';
        }
        return $result;
    }
    public function handleDayInMonth($monthNearly)
    {
        $startDate = \DateTime::createFromFormat('d-m-Y', '01-' . $monthNearly);
        $countDate = $startDate->format('t');
        $listDate = [];
        for ($i = 1; $i <= $countDate; $i++) {
            $ngay = $startDate->format('d-m-Y');
            $listDate[] = $ngay;
            $startDate->modify('+1 day');
        }
        return $listDate;
    }
}
