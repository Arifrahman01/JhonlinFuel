<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiptTransferDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'receipt_transfer_details';

    protected $guarded = [
        'id',
    ];
}
