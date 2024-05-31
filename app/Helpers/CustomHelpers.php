<?php

use App\Helpers\ApiResponse;
use App\Models\Company;
use App\Models\User;
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
