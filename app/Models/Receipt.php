<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends BaseModel
{
    protected $table = 'rcv_pos';
    
    protected $guarded = [
        'id',
    ];

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
