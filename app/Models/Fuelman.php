<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fuelman extends BaseModel
{
    protected $table = 'fuelmans';

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

    public function hasDataByNik(): bool
    {
        if (Issue::where('fuelman', $this->nik)->exists()) {
            return true;
        }
        return false;
    }
}
