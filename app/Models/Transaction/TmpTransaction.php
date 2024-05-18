<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;
use App\Models\Uom;

class TmpTransaction extends BaseModel
{
    protected $table = 'material_transactions_tmp';

    protected $guarded = [
        'id',
    ];
}
