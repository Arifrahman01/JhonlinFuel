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
}
