<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlocTransfer extends BaseModel
{
    protected $table = 'storage_transfers';

    public function fromCompany() : BelongsTo
    {
        return $this->belongsTo(Company::class, 'from_company_id', 'id');
    }

   public function fromPlant() : BelongsTo
   {
       return $this->belongsTo(Plant::class, 'from_plant_id', 'id');
   }
    public function toCompany() : BelongsTo
    {
        return $this->belongsTo(Company::class, 'to_company_id', 'id');
    }
    public function toPlant() : BelongsTo
    {
        return $this->belongsTo(Plant::class, 'to_plant_id', 'id');
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
