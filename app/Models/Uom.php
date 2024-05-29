<?php

namespace App\Models;

class Uom extends BaseModel
{
    protected $table = 'uoms';

    public function scopeSearch($query, $filters)
    {
        return $query->when($filters, function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('uom_code', 'like', '%' . $value . '%')
                      ->orWhere('uom_name', 'like', '%' . $value . '%');
            });
        });
    }
}
