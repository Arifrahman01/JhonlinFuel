<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlocTransfer extends BaseModel
{
    protected $table = 'storage_transfers';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class, 'plant_id', 'id');
    }
    public function sloc() : BelongsTo
    {
        return $this->belongsTo(Sloc::class, 'sloc_id', 'id');
    }
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_id', 'id');
    }

}
