<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\ReceiptTransfer;
use Livewire\Component;

class ReceiptTransferPostingList extends Component
{
    public $dateFilter;
    public function render()
    {
        $userCompanyCode = Company::find(auth()->user()->company_id)->company_code;
        $rcvTransfers = ReceiptTransfer::with([
            'fromCompany',
            'toCompany',
            'fromWarehouse',
            'toWarehouse',
        ])
            ->where('from_company_code', $userCompanyCode)
            ->whereNotNull('posting_no')
            ->when($this->dateFilter, function ($query, $dateFilter) {
                $query->where('trans_date', $dateFilter);
            })
            ->paginate(10);

        return view('livewire.receipt-transfer-posting-list', compact('rcvTransfers'));
    }
}
