<?php
return [
    //chấm công
    "ca1_start_time" => "06:00:00",
    "ca1_end_time" => "20:30:00",
    "list_category" => [
        'working_hours' => 'Nhân viên hành chính (07:30 - 17:00)',
        'qc_day' => 'Nhân viên QC Ca Ngày (07:30 - 19:30)',
        'rotating_shift_mk' => 'Nhóm Đi Xoay Ca - Hàng Chợ',
        'rotating_shift_jp' => 'Nhóm Đi Xoay Ca - Hàng Nhật',
        'technical' => 'Nhóm Kỹ Thuật'
    ],
    "list_category_ca1" => ['working_hours', 'qc_day'],
    "list_category_ca2" => ['rotating_shift_mk', 'rotating_shift_jp', 'technical'],
    "ca2_min_start_time" => "18:00:00",
    "ca2_max_end_time" => "09:00:00",
    "ca1_work_start_time" => "07:30:00",
    "ca1_work_end_time_wh" => "17:00:00",
    "ca1_work_end_time_qd" => "19:30:00",
    "ca1_break_time_wh" => 90,
    "ca1_break_time_qd" => 60,
    "ca2_break_time" => 60,
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
];
