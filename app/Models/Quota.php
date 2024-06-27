<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quota extends BaseModel
{
    protected $table = 'quotas';

    public function Company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

}
