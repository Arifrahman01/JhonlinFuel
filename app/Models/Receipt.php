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

}
