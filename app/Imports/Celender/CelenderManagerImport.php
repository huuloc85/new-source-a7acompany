<?php

namespace App\Imports\Celender;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CelenderManagerImport implements WithMultipleSheets
{
    public $celenderId;
    public function __construct($celenderId)
    {
        $this->celenderId = $celenderId;
    }

    public function sheets(): array
    {
        return [
            'HÀNG NHẬT' => new CelenderHNHCImport($this->celenderId),
            'TRỰC NHÀ ĂN' => new CelenderEatRoomImport($this->celenderId),
            'ĐỔ RÁC WC' => new CelenderWCImport($this->celenderId),
            'TRỰC WC NỮ' => new CelenderWCCleanWomenImport($this->celenderId),
            'TRỰC WC NAM' => new CelenderWCCleanMenImport($this->celenderId),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        // E.g. you can log that a sheet was not found.
        info("Sheet {$sheetName} was skipped");
    }
}
