<?php

namespace App\Imports;

use App\Models\Company;
use App\Models\ReceiptTransfer;
use App\Models\Uom;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ReceiptTransferImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $rows->shift();
        $uom = Uom::first();
        foreach ($rows as $row) {

            if (!is_null($row[0])) {
                $rcvTransfer = new ReceiptTransfer();
                $rcvTransfer->trans_type = $row[0];
                $rcvTransfer->trans_date = Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($row[1] - 2)->format('Y-m-d');
                $rcvTransfer->from_company_code = strtoupper($row[2]);
                $rcvTransfer->from_warehouse = $row[3];
                $rcvTransfer->to_company_code = strtoupper($row[4]);
                $rcvTransfer->to_warehouse = $row[5];
                $rcvTransfer->transportir = $row[6];
                $rcvTransfer->material_code = $row[7];
                $rcvTransfer->qty = $row[8];
                $rcvTransfer->uom = $uom->uom_code;
                $rcvTransfer->save();
            }
        }
    }
}
