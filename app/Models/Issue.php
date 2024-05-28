<?php

namespace App\Models;

use Faker\Provider\Base;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Issue extends BaseModel
{
    protected $table = 'issues';
    protected $guarded = [
        'id',
    ];

    public static function sumQty($date, $perPage = 10)
    {
        $summary = self::select('warehouse', 'trans_date', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('warehouse', 'trans_date')
            ->where('trans_date', $date)
            ->whereNull('posting_no')
            ->paginate($perPage);
        $details = self::where('trans_date', $date)
            ->whereNull('posting_no')
            ->get()
            ->groupBy('warehouse');
        $result = collect();
        foreach ($summary as $item) {
            $result->push([
                'summary' => $item,
                'details' => $details[$item->warehouse] ?? [],
            ]);
        }
    
        return $result;
    }
}
