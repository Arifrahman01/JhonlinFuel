<?php

namespace App\Models;

use App\Models\Transaction\TmpTransaction;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends BaseModel
{
    protected $table = 'activities';

    public function company() :BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function hasData(): bool
    {
        if (TmpTransaction::where('activity', $this->activity_code)->exists()) {
            return true;
        }
        if (Transaction::where('activity_id', $this->id)->exists()) {
            return true;
        }
        return false;
    }
}
