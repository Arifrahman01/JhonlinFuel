<?php

namespace App\Livewire\Transaction;

use App\Models\ReceiptTransfer;
use Livewire\Component;
use Livewire\WithPagination;

class ReceiptTransferList extends Component
{

    use WithPagination;
    protected $listeners = ['refreshPage'];
    public function render()
    {
        $userCompanyCode = 'jb';
        $rcvTransfers = ReceiptTransfer::with([
            'fromCompany',
            'toCompany',
            'fromWarehouse',
            'toWarehouse',
        ])
            ->where('from_company_code', $userCompanyCode)
            ->paginate(10);

        return view('livewire.transaction.receipt-transfer-list', compact('rcvTransfers'));
    }

    public function delete($id)
    {
        try {
            $rcvTransfer = ReceiptTransfer::findOrFail($id);
            $rcvTransfer->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
