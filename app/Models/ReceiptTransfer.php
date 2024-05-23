<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptTransfer extends BaseModel
{
    protected $table = 'rcv_transfers';

    public function fromCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'from_company_code', 'company_code');
    }

    public function toCompany(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'to_company_code', 'company_code');
    }

    public function fromWarehouse(): BelongsTo
    {
        return $this->belongsTo(Sloc::class, 'from_warehouse', 'sloc_code');
    }

    public function toWarehouse(): BelongsTo
    {
        return $this->belongsTo(Sloc::class, 'to_warehouse', 'sloc_code');
    }
}
