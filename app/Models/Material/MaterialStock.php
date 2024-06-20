<?php

namespace App\Models\Material;

use App\Models\BaseModel;
use App\Models\Company;

class MaterialStock extends BaseModel
{
    protected $table = 'material_stocks';

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
