<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestDetail extends Model
{
    use HasFactory, SoftDeletes;

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
