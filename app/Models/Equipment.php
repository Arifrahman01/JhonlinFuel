<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends BaseModel
{
    protected $table = 'equipments';

    public function company() :BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    public function plant() :BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }
    public function hasDataById(): bool
    {
        return false;
    }

    public function hasDataByNo(): bool
    {
        if (Issue::where('equipment_no', $this->equipment_no)->exists()) {
            return true;
        }
        return false;
    }
}
