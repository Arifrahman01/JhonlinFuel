<?php

namespace App\Imports;

use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ReceiptImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $rows->shift();
        foreach ($rows as $row) {
            if (!is_null($row[0])) {
                $receipt = new Receipt();
                $receipt->company_code = $row[0];
                $receipt->trans_type = $row[1];
                $receipt->po_no = $row[2];
                $receipt->do_no = $row[3];
                $receipt->trans_date = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($row[4] - 2)->format('Y-m-d');
                $receipt->location = $row[5];
                $receipt->warehouse = $row[6];
                $receipt->transportir = $row[7];
                $receipt->material_code = $row[8];
                $receipt->qty = $row[9];
                $receipt->uom = 'L';
                $receipt->reference = $row[10];
                $receipt->save();
            }
        }
    }
}
