<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserRole extends Pivot
{
    protected $table = 'user_roles';

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'user_role_companies', 'user_role_id', 'company_id', 'id', 'id');
    }
}
