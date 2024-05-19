<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;

class Transaction extends BaseModel
{
    protected $table = 'material_transactions';

    protected $guarded = [
        'id',
    ];
}
