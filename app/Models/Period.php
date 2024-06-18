<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Period extends BaseModel
{
    protected $table = 'periods';

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_period')->withPivot('status');
    }

    public function hasDataById(): bool
    {
        return false;
    }
}
