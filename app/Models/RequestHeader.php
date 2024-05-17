<?php

namespace App\Models;

class RequestHeader extends BaseModel
{
    protected $table = 'request_headers';

    protected $guarded = [
        'id',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_id = auth()->id();
        });
    }

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
