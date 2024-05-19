<?php

namespace App\Models\Adjustment;

use App\Models\BaseModel;
use App\Models\Company;

class AdjustmentHeader extends BaseModel
{
    protected $table = 'adjustment_headers';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
