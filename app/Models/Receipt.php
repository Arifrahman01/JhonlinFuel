<?php

namespace App\Models;

use App\Models\Material\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends BaseModel
{
    protected $table = 'rcv_pos';
    
    protected $guarded = [
        'id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_code', 'company_code');
    }
    
    public function slocs()
    {
        return $this->belongsTo(Sloc::class, 'warehouse', 'sloc_code');
    }

    public function plants()
    {
        return $this->belongsTo(Plant::class, 'location', 'plant_code');
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
                $query->where('po_no', 'like', '%' . $value . '%')
                      ->orWhere('do_no', 'like', '%' . $value . '%');
            });
        });
    }
    
    

}
