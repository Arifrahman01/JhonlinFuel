<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends BaseModel
{
    protected $table = 'activities';

    public function company() :BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
