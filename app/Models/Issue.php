<?php

namespace App\Models;

use App\Models\Material\Material;
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

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_code', 'company_code');
    }
    public function departments()
    {
        return $this->belongsTo(Department::class, 'department', 'department_code');
    }

    public function fuelmans()
    {
        return $this->belongsTo(Fuelman::class, 'fuelman', 'nik');
    }

    public function slocs()
    {
        return $this->belongsTo(Sloc::class, 'warehouse', 'sloc_code');
    }

    public function plants()
    {
        return $this->belongsTo(Plant::class, 'location', 'plant_code');
    }

    public function equipments()
    {
        return $this->belongsTo(Equipment::class, 'equipment_no', 'equipment_no');
    }
    public function activitys()
    {
        return $this->belongsTo(Activity::class, 'activity', 'activity_code');
    }
    public function materials()
    {
        return $this->belongsTo(Material::class, 'material_code', 'material_code');
    }


    public static function sumQty($date, $perPage = 10)
    {
        $summary = self::select('company_code', 'warehouse', 'trans_date', DB::raw('SUM(qty) as total_qty'), DB::raw('GROUP_CONCAT(id) as detail_ids'))
            ->groupBy(['warehouse', 'trans_date', 'company_code'])
            ->where('trans_date', $date)
            ->whereNull('posting_no')
            ->paginate($perPage);
        $details = self::where('trans_date', $date)
            ->whereNull('posting_no')
            ->get()
            ->groupBy(function($item) {
                return $item->warehouse . '|' . $item->trans_date . '|' . $item->company_code;
            });

        $result = collect();
        foreach ($summary as $item) {
            $key = $item->warehouse . '|' . $item->trans_date . '|' . $item->company_code;
            $result->push([
                'summary' => $item,
                'details' => $details[$key] ?? [],
            ]);
        }

        return $result;
    }
}
