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
    function allowedCompanyId($otorisasi)
    {
        $user = User::find(auth()->id());
        $roleCode = data_get($user, 'roles.*.code');
        $companyIds = data_get(Company::all(), '*.id');
        if (!in_array('sa', $roleCode)) {
            $companyIds = [];
            foreach ($user->roles as $role) {
                if (in_array($otorisasi, data_get($role, 'permissions.*.permission_code'))) {
                    $companyIds = data_get($role, 'pivot.companies.*.id');
                    break;
                }
            }
        }
        return $companyIds;
    }
}
if (!function_exists('allowedCompanyCode')) {
    function allowedCompanyCode($otorisasi)
    {
        $user = User::with(['roles'])->find(auth()->id());
        $roleCode = data_get($user, 'roles.*.code');
        $companyCodes = data_get(Company::all(), '*.company_code');
        if (!in_array('sa', $roleCode)) {
            $companyCodes = [];
            foreach ($user->roles as $role) {
                if (in_array($otorisasi, data_get($role, 'permissions.*.permission_code'))) {
                    $companyCodes = data_get($role, 'pivot.companies.*.company_code');
                    break;
                }
            }
        }
        return $companyCodes;
    }
}
