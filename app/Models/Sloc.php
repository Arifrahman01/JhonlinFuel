<?php

namespace App\Models;

use App\Models\Adjustment\AdjustmentDetail;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sloc extends BaseModel
{
    protected $table = 'storage_locations';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class, 'plant_id', 'id');
    }

    public function currentStock(): BelongsTo
    {
        return $this->belongsTo(MaterialStock::class, 'sloc_id', 'id');
    }

    public function periodStock(): BelongsTo
    {
        return $this->belongsTo(StockClosure::class, 'sloc_id', 'id');
    }

    public function hasDataById(): bool
    {
        if (AdjustmentDetail::where('sloc_id', $this->id)->exists()) {
            return true;
        }
        if (MaterialMovement::where('sloc_id', $this->id)->exists()) {
            return true;
        }
        $materialStock = MaterialStock::where('sloc_id', $this->id)
            ->first();
        // if ($materialStock->qty_soh > 0 || $materialStock->qty_intransit > 0) {
        //     return true;
        // }
        if ($materialStock && ($materialStock->qty_soh > 0 || $materialStock->qty_intransit > 0)) {
            return true;
        }
        return false;
    }

    public function hasDataByCode(): bool
    {
        if (Receipt::where('warehouse', $this->sloc_code)
            ->exists()
        ) {
            return true;
        }
        if (Transfer::where('from_warehouse', $this->sloc_code)
            ->orWhere('to_warehouse', $this->sloc_code)
            ->exists()
        ) {
            return true;
        }
        if (ReceiptTransfer::where('from_warehouse', $this->sloc_code)
            ->orWhere('to_warehouse', $this->sloc_code)
            ->exists()
        ) {
            return true;
        }
        if (Issue::where('warehouse', $this->sloc_code)->exists()) {
            return true;
        }

        return false;
    }
}
