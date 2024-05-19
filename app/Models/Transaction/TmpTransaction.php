<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;
use App\Models\Uom;
use Illuminate\Support\Facades\DB;

class TmpTransaction extends BaseModel
{
    protected $table = 'material_transactions_tmp';

    protected $guarded = [
        'id',
    ];

    public static function sumQty($date)
    {
        $query =  self::select('fuel_warehouse', 'trans_date', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('fuel_warehouse', 'trans_date')
            ->where('trans_date', $date);
        return $query;
    }
    public static function sumQty2($date, $perPage = 10)
    {
        // Get summarized quantities with pagination
        $summary = self::select('fuel_warehouse', 'trans_date', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('fuel_warehouse', 'trans_date')
            ->where('trans_date', $date)
            ->paginate($perPage);
    
        // Get all the details without pagination
        $details = self::where('trans_date', $date)
            ->get()
            ->groupBy('fuel_warehouse');
    
        // Combine summarized quantities with all details
        $result = collect();
        foreach ($summary as $item) {
            $result->push([
                'summary' => $item,
                'details' => $details[$item->fuel_warehouse] ?? [],
            ]);
        }
    
        return $result;
    }
    
}
