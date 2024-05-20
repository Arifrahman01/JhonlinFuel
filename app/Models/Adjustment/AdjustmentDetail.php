<?php

namespace App\Models\Adjustment;

use App\Models\BaseModel;
use App\Models\Plant;
use App\Models\Sloc;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdjustmentDetail extends BaseModel
{
    protected $table = 'adjustment_details';

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class, 'plant_id', 'id');
    }

    public function sloc(): BelongsTo
    {
        return $this->belongsTo(Sloc::class, 'sloc_id', 'id');
    }
}
