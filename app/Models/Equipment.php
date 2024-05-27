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
}
