<?php
return [
    // chấm công
    "ca1_start_time" => "06:00:00",
    "ca1_end_time" => "20:30:00",
    "list_category" => [
        'working_hours' => 'Nhân viên hành chính (07:30 - 17:00)',
        'qc_day' => 'Nhân viên QC Ca Ngày (07:30 - 19:30)',
        'rotating_shift_mk' => 'Nhóm Đi Xoay Ca - Hàng Chợ',
        'rotating_shift_jp' => 'Nhóm Đi Xoay Ca - Hàng Nhật',
        'technical' => 'Nhóm Kỹ Thuật',
    ],
    "list_category_ca1" => ['working_hours', 'qc_day'],
    "list_category_ca2" => ['rotating_shift_mk', 'rotating_shift_jp', 'technical'],
    "ca2_min_start_time" => "18:00:00",
    "ca2_max_end_time" => "09:00:00",
    "ca1_work_start_time" => "07:30:00",
    "ca1_work_end_time_wh" => "17:00:00",
    "ca1_work_end_time_qd" => "19:30:00",
    // "ca1_break_time_wh" => 90,
    // "ca1_break_time_qd" => 60,
    // "ca2_break_time" => 60,
    "special_learn" => "07:00:00",
    "special_overtime" => "16:00:00",
    "ca2_work_start_time" => "19:30:00",
    "ca2_work_end_time" => "07:30:00",
    "over_time_start_qd" => "16:30:00",
    "over_time_start_wh" => "17:00:00",
    "over_time_start_ca2" => "04:30:00",
    "over_time_end_ca1" => "20:30:00",
    "over_time_end_ca2" => "09:00:00",
    "time_work" => 8,
    "shift_1" => "N",
    "shift_2" => "D",
    "ca2_check_start_time" => "12:00:00",

    // Thời gian nghỉ
    "break_times" => [
        'ca_hanh_chinh' => [
            'schedule' => [
                ['time' => '09:30', 'duration' => 15],
                ['time' => '12:00', 'duration' => 60],
                ['time' => '14:30', 'duration' => 15],
            ],
        ],
        'ca_ngay' => [
            'schedule' => [
                ['time' => '09:30', 'duration' => 5],
                ['time' => '11:20', 'duration' => 40],
                ['time' => '14:30', 'duration' => 5],
                // ['time' => '15:50', 'duration' => 10],
                ['time' => '17:00', 'duration' => 10],
            ],
        ],
        'sx_ca_1' => [
            'schedule' => [
                ['time' => '09:30', 'duration' => 10],
                ['time' => '11:20', 'duration' => 30],
                ['time' => '14:30', 'duration' => 10],
                ['time' => '17:00', 'duration' => 10],
            ],
        ],
        'sx_ca_2' => [
            'schedule' => [
                ['time' => '21:30', 'duration' => 10],
                ['time' => '23:30', 'duration' => 30],
                ['time' => '02:30', 'duration' => 10],
                ['time' => '05:00', 'duration' => 10],
            ],
        ],
    ],
];
