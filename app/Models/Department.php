<?php

namespace App\Models;

use App\Models\Transaction\TmpTransaction;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends BaseModel
{
    protected $table = 'departments';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class, 'plant_id', 'id');
    }

    public function hasDataById(): bool
    {
        return false;
    }

    public function hasDataByCode(): bool
    {
        if (Issue::where('department', $this->department_code)->exists()) {
            return true;
        }
        // if (Transaction::where('department', $this->department_code)->exists()) {
        //     return true;
        // }
        return false;
    }
}
