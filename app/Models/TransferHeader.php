<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferHeader extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transfer_headers';

    protected $guarded = [
        'id',
    ];
}
