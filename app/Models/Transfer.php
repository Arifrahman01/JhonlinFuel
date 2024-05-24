<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends BaseModel
{
    protected $table = 'transfers';

    protected $guarded = [
        'id',
    ];

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
