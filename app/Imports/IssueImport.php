<?php

namespace App\Imports;

use App\Models\Issue;
use App\Models\Transaction\TmpTransaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class IssueImport implements ToCollection
{
    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $rows->shift();
        foreach ($rows as $row) {
            if (!is_null($row[0])) {
                $transaction = new Issue();
                $transaction->company_code = $row[0];
                $transaction->warehouse = $row[1];
                $transaction->trans_type = $row[2];
                $transaction->trans_date =  Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($row[3] - 2)->format('Y-m-d');
                $transaction->fuelman = $row[4];
                $transaction->equipment_no = $row[5];
                $transaction->location = $row[6];
                $transaction->department = $row[7];
                $transaction->activity = $row[8];
                $transaction->material_code = $row[9];
                $transaction->uom = 'L';
                $transaction->qty = $row[10];
                $transaction->statistic_type = $row[11];
                $transaction->meter_value = $row[12];
                $transaction->patch = $this->fileName;
                $transaction->save();
            }
        }
    }
}
