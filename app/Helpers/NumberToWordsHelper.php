<?php

namespace App\Helpers;

class NumberToWordsHelper
{
    protected static $ones = [
        '',
        'một',
        'hai',
        'ba',
        'bốn',
        'năm',
        'sáu',
        'bảy',
        'tám',
        'chín',
    ];

    protected static $teens = [
        'mười',
        'mười một',
        'mười hai',
        'mười ba',
        'mười bốn',
        'mười lăm',
        'mười sáu',
        'mười bảy',
        'mười tám',
        'mười chín',
    ];

    protected static $tens = [
        '',
        '',
        'hai mươi',
        'ba mươi',
        'bốn mươi',
        'năm mươi',
        'sáu mươi',
        'bảy mươi',
        'tám mươi',
        'chín mươi',
    ];

    protected static $units = ['', 'nghìn', 'triệu', 'tỷ'];

    public static function convert($number)
    {
        if (! is_numeric($number)) {
            return false;
        }

        $number = (int) $number;

        if ($number === 0) {
            return 'Không';
        }

        $words = [];
        $unitIndex = 0;

        while ($number > 0) {
            $chunk = $number % 1000;
            if ($chunk > 0) {
                $words[] = self::convertChunk($chunk).' '.self::$units[$unitIndex];
            }
            $number = (int) ($number / 1000);
            $unitIndex++;
        }

        return ucfirst(trim(implode(' ', array_reverse($words))));
    }

    private static function convertChunk($number)
    {
        $hundreds = (int) ($number / 100);
        $remainder = $number % 100;
        $words = [];

        if ($hundreds > 0) {
            $words[] = self::$ones[$hundreds].' trăm';
        }

        if ($remainder > 0) {
            if ($remainder < 10) {
                $words[] = self::$ones[$remainder];
            } elseif ($remainder < 20) {
                $words[] = self::$teens[$remainder - 10];
            } else {
                $tens = (int) ($remainder / 10);
                $ones = $remainder % 10;
                $words[] = self::$tens[$tens];
                if ($ones > 0) {
                    $words[] = self::$ones[$ones];
                }
            }
        }

        return implode(' ', $words);
    }
}
