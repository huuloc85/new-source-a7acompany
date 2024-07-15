<?php

namespace App\Helpers;

class Status
{
    public static function getStatusValue($category_celender_name)
    {
        switch ($category_celender_name) {
            case 'Nhóm Đi Xoay Ca - Hàng Nhật':
            case 'Nhóm Kỹ Thuật':
            case 'Nhóm Đi Xoay Ca - Hàng Chợ':
                return 1;
            case 'Nhóm QC Ca Ngày':
                return 2;
            default:
                return 0;
        }
    }
}
