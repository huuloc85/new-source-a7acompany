<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\CelenderDetailHNHC;

trait CelenderDetailTrait
{
    public function getCelenderDetails(Request $request, $id)
    {
        $today = now();
        $currentDay = $today->day;
        $day = request('day', $currentDay);

        $employeesToday = CelenderDetailHNHC::where('celender_id', $id)
            ->whereHas('employee', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->get()
            ->filter(function ($detail) use ($day) {
                $columnName = "day" . $day;
                $workValues = ['N', 'LN', 'TC', 'D'];
                $columnValue = $detail->$columnName;
                return in_array($columnValue, $workValues);
            });

        foreach ($employeesToday as $detail) {
            $currentDayValue = $detail->{'day' . $day};
            if (in_array($currentDayValue, ['N', 'LN'])) {
                $detail->shift = 'Ca 1';
            } elseif (in_array($currentDayValue, ['TC', 'D'])) {
                $detail->shift = 'Ca 2';
            } else {
                $detail->shift = 'Nghỉ Làm';
            }
        }

        $employeesTodayCount = $employeesToday->count();

        return [
            'employeesToday' => $employeesToday,
            'today' => $today,
            'day' => $day,
            'employeesTodayCount' => $employeesTodayCount,
            'currentDay' => $currentDay,
        ];
    }
}
