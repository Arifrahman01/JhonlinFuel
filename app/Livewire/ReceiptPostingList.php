<?php

namespace App\Livewire;

use App\Models\Receipt;
use Livewire\Component;

class ReceiptPostingList extends Component
{
    public $dateFilter;
    public function render()
    {
        $receipts = Receipt::whereNotNull('posting_no')
            ->when($this->dateFilter, function ($query, $dateFilter) {
                $query->where('trans_date', $dateFilter);
            })
            // ->search($this->filter_search)
            ->paginate(10);
        return view('livewire.receipt-posting-list', compact('receipts'));
    }
}
