<?php

use App\Helpers\ApiResponse;
use App\Models\Company;
use App\Models\Period;
use App\Models\User;
use Carbon\Carbon;
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
if (!function_exists('allowedCompanyId')) {
    function allowedCompanyId($otorisasi): array
    {
        $user = User::with('roles.permissions')->find(auth()->id());
        $roleCode = data_get($user, 'roles.*.role_code');

        if (in_array('sa', $roleCode)) {
            return data_get(Company::all(), '*.id');
        }
        foreach ($user->roles as $role) {
            if (in_array($otorisasi, data_get($role, 'permissions.*.permission_code'))) {
                return data_get($role, 'pivot.companies.*.id');
            }
        }

        return [];
    }
}
if (!function_exists('allowedCompanyCode')) {
    function allowedCompanyCode($otorisasi): array
    {
        $user = User::with('roles.permissions')->find(auth()->id());
        $roleCode = data_get($user, 'roles.*.role_code');

        if (in_array('sa', $roleCode)) {
            return data_get(Company::all(), '*.company_code');
        }

        foreach ($user->roles as $role) {
            if (in_array($otorisasi, data_get($role, 'permissions.*.permission_code'))) {
                return data_get($role, 'pivot.companies.*.company_code');
            }
        }

        return [];
    }
}
if (!function_exists('allowedCompanyCode')) {
    function allowedCompanyCode($otorisasi): array
    {
        $user = User::with('roles.permissions')->find(auth()->id());
        $roleCode = data_get($user, 'roles.*.role_code');

        if (in_array('sa', $roleCode)) {
            return data_get(Company::all(), '*.company_code');
        }

        foreach ($user->roles as $role) {
            if (in_array($otorisasi, data_get($role, 'permissions.*.permission_code'))) {
                return data_get($role, 'pivot.companies.*.company_code');
            }
        }

        return [];
    }
}

if (!function_exists('checkPeriod')) {
    function checkOpenPeriod($companyId, $transDate): bool
    {
        $dateToCheck = Carbon::parse($transDate);

        return Company::where('id', $companyId)
            ->whereHas('periods', function ($query) use ($dateToCheck) {
                $query->where('company_period.status', 'open')
                    ->where('start_date', '<=', $dateToCheck)
                    ->where('end_date', '>=', $dateToCheck);
            })
            ->exists();
    }
}

if (!function_exists('getlistTahun')) {
    function getListTahun()
    {
        $startYear = 2024;
        $nowYear = (int)date("Y");
        $listTahun = [];

        for ($tahun = $startYear; $tahun <= $nowYear; $tahun++) {
            $listTahun[] = $tahun;
        }

        return $listTahun;
    }
}

if (!function_exists('getlistBulan')) {
    function getListBulan()
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }
}
