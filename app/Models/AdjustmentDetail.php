<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdjustmentDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'adjustment_details';

    protected $guarded = [
        'id',
    ];
}
