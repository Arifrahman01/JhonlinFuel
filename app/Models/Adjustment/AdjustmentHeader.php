<?php

namespace App\Models\Adjustment;

use App\Models\BaseModel;
use App\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdjustmentHeader extends BaseModel
{
    protected $table = 'adjustment_headers';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(AdjustmentDetail::class, 'header_id', 'id');
    }

    public function scopeSearch($query, $filters)
    {
        return $query->when($filters['adjNo'], function ($query, $value) {
            $query->where('adjustment_no', 'like', '%' . $value . '%');
        });
    }
}
