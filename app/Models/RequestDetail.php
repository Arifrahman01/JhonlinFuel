<?php

namespace App\Models;

class RequestDetail extends BaseModel
{
    protected $table = 'request_details';

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

    public function uom() 
    {
        return $this->belongsTo(Uom::class, 'uom_id', 'id');
    }
}
