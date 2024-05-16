<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestHeader extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'request_headers';

    protected $guarded = [
        'id',
    ];

    public function details()
    {
        return $this->hasMany(RequestDetail::class, 'header_id');
    }


    public function company() 
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function period() 
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

}
