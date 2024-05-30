<?php

namespace App\Models;

use App\Models\Adjustment\AdjustmentDetail;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Transaction\TmpTransaction;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plant extends BaseModel
{
    protected $table = 'plants';

    public function Company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function hasDataById(): bool
    {
        if (AdjustmentDetail::where('plant_id', $this->id)->exists()) {
            return true;
        }
        if (Department::where('plant_id', $this->id)->exists()) {
            return true;
        }
        if (Equipment::where('plant_id', $this->id)->exists()) {
            return true;
        }
        if (Fuelman::where('plant_id', $this->id)->exists()) {
            return true;
        }
        if (MaterialMovement::where('plant_id', $this->id)->exists()) {
            return true;
        }
        if (MaterialStock::where('plant_id', $this->id)->exists()) {
            return true;
        }
        // if (Transaction::where('location_id', $this->id)->exists()) {
        //     return true;
        // }
        if (Sloc::where('plant_id', $this->id)->exists()) {
            return true;
        }
        return false;
    }

    public function hasDataByCode(): bool
    {
        if (Receipt::where('location', $this->plant_code)->exists()) {
            return true;
        }
        if (Issue::where('location', $this->plant_code)->exists()) {
            return true;
        }
        return false;
    }
}
