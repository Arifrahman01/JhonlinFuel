<?php

namespace App\Models;

use App\Models\Material\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends BaseModel
{
    protected $table = 'transfers';

    protected $guarded = [
        'id',
    ];
    
    public function fromCompany()
    {
        return $this->belongsTo(Company::class, 'from_company_code', 'company_code');
    }
    
    public function toCompany()
    {
        return $this->belongsTo(Company::class, 'to_company_code', 'company_code');
    }

    public function fromSloc()
    {
        return $this->belongsTo(Sloc::class, 'from_warehouse', 'sloc_code');
    }

    public function toSloc()
    {
        return $this->belongsTo(Sloc::class, 'to_warehouse', 'sloc_code');
    }

    public function equipments()
    {
        return $this->belongsTo(Equipment::class, 'transportir', 'equipment_no');
    }

    public function materials()
    {
        return $this->belongsTo(Material::class, 'material_code', 'material_code');
    }

    public function scopeSearch($query, $filters)
    {
        return $query->when($filters, function ($query, $value) {
            $query->where(function ($query) use ($value) {
                $query->where('posting_no', 'like', '%' . $value . '%')
                      ->orWhere('transportir', 'like', '%' . $value . '%');
            });
        });
    }
    
}
