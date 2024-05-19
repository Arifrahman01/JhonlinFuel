<?php

use App\Helpers\ApiResponse;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Response;

if (!function_exists('selected')) {
    function selected($a, $b)
    {
        return $a == $b ? 'selected' : '';
    }
}
if (!function_exists('dataNotFond')) {
    function dataNotFond($int = 5)
    {
        return "<tr>
                    <td class=\"text-center\">&nbsp;<i class=\"fa fa-info-circle\"></i></td>
                    <td colspan=\"$int\">Data not found</td>
                </tr>";
    }
}
if (!function_exists('toNumber')) {
    function toNumber($string)
    {
        if (is_numeric($string)) {
            return intval($string);
        } else {
            return 0;
        }
    }
}
