<?php

namespace App\Models;

use App\Models\Adjustment\AdjustmentDetail;
use App\Models\Adjustment\AdjustmentHeader;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Transaction\TmpTransaction;
use App\Models\Transaction\Transaction;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends BaseModel
{
    protected $table = 'companies';

    public function scopeAllowed($query, $otorisasi)
    {
        if (in_array('sa', data_get(auth()->user(), 'roles.*.code'))) {
            return $query;
        }
        return $query->whereIn('id', allowedCompanyId($otorisasi));
    }

    public function plants(): HasMany
    {
        return $this->hasMany(Plant::class, 'company_id', 'id');
    }
    public function slocs(): HasMany
    {
        return $this->hasMany(Sloc::class, 'company_id', 'id');
    }

    public function periods(): BelongsToMany
    {
        return $this->belongsToMany(Period::class, 'company_period')->withPivot('status');
    }

    public function hasDataById(): bool
    {
        if (Activity::where('company_id', $this->id)->exists()) {
            return true;
        }
        if (AdjustmentHeader::where('company_id', $this->id)->exists()) {
            return true;
        }
        if (AdjustmentDetail::where('company_id', $this->id)->exists()) {
            return true;
        }
        if (Department::where('company_id', $this->id)->exists()) {
            return true;
        }
        if (Equipment::where('company_id', $this->id)->exists()) {
            return true;
        }
        if (Fuelman::where('company_id', $this->id)->exists()) {
            return true;
        }
        if (MaterialMovement::where('company_id', $this->id)->exists()) {
            return true;
        }
        $materialStock = MaterialStock::where('company_id', $this->id)
            ->first();
        if ($materialStock && ($materialStock->qty_soh > 0 || $materialStock->qty_intransit > 0)) {
            return true;
        }
        // if (Transaction::where('company_id', $this->id)->exists()) {
        //     return true;
        // }
        if (Plant::where('company_id', $this->id)->exists()) {
            return true;
        }
        if (Sloc::where('company_id', $this->id)->exists()) {
            return true;
        }
        if (User::where('company_id', $this->id)->exists()) {
            return true;
        }
        return false;
    }
    public function hasDataByCode(): bool
    {
        if (Receipt::where('company_code', $this->company_code)->exists()) {
            return true;
        }
        if (Transfer::where('from_company_code', $this->company_code)
            ->orWhere('to_company_code', $this->company_code)
            ->exists()
        ) {
            return true;
        }
        if (ReceiptTransfer::where('from_company_code', $this->company_code)
            ->orWhere('to_company_code', $this->company_code)
            ->exists()
        ) {
            return true;
        }
        // if (TmpTransaction::where('company_code', $this->company_code)->exists()) {
        //     return true;
        // }
        return false;
    }
}
