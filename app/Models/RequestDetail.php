<?php

namespace App\Models;

class RequestDetail extends BaseModel
{
    protected $table = 'request_details';

    protected $guarded = [
        'id',
    ];
    public function uom() 
    {
        return $this->belongsTo(Uom::class, 'uom_id', 'id');
    }
}
