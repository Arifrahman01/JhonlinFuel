<?php

namespace App\Imports;

use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class TransferImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $rows->shift();
        foreach ($rows as $row) {
            if (!is_null($row[0])) {
                $transfer = new Transfer();
                $transfer->trans_type = $row[0];
                $transfer->trans_date = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($row[1] - 2)->format('Y-m-d');
                $transfer->from_company_code = $row[2];
                $transfer->from_warehouse = $row[3];
                $transfer->to_company_code = $row[4];
                $transfer->to_warehouse = $row[5];
                $transfer->transportir = $row[6];
                $transfer->material_code = $row[7];
                $transfer->qty = $row[8];
                $transfer->uom = 'L';
                $transfer->save();
            }
        }
    }
}
