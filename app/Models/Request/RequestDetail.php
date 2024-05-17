<?php

namespace App\Models\Request;

use App\Models\BaseModel;
use App\Models\Uom;

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
