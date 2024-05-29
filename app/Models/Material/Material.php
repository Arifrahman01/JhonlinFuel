<?php

namespace App\Models\Material;

use App\Models\BaseModel;

class Material extends BaseModel
{
    protected $table = 'materials';

    public function scopeSearch($query, $filters)
    {
        return $query->when($filters, function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('material_code', 'like', '%' . $value . '%')
                      ->orWhere('material_mnemonic', 'like', '%' . $value . '%')
                      ->orWhere('part_no', 'like', '%' . $value . '%')
                      ->orWhere('material_description', 'like', '%' . $value . '%');
            });
        });
    }
}
