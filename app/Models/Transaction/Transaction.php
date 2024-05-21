<?php

namespace App\Models\Transaction;

use App\Models\BaseModel;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class Transaction extends BaseModel
{
    protected $table = 'material_transactions';

    protected $guarded = [
        'id',
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public static function sumQty($date, $perPage = 10)
    {
        $summary = self::select('posting_no', 'location_name' ,'trans_date', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('posting_no','location_id','location_name','trans_date')
            ->where('trans_date', $date)
            ->paginate($perPage);

        $summary->getCollection()->transform(function ($item) {
            $item->total_qty = number_format((float) $item->total_qty, 2, '.', '');
            return $item;
        });
        $details = self::where('trans_date', $date)
            ->get()
            ->groupBy('posting_no','location_id','location_name','trans_date');
        $result = collect();
        foreach ($summary as $item) {
            $result->push([
                'summary' => $item,
                'details' => $details[$item->posting_no] ?? [],
            ]);
        }
    
        return $result;
    }
}
