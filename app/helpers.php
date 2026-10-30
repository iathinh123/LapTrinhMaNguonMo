<?php

if (! function_exists('tien')) {
    /** Định dạng tiền Việt: 1,200,000 ₫ */
    function tien(mixed $so): string
    {
        return number_format((float) ($so ?? 0), 0, '.', ',').' ₫';
    }
}
