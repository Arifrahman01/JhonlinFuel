<?php

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferLoader extends Model
{
    protected $table = 'rcv_transfer_loaders';

    protected $guarded = [
        'id',
    ];
}
