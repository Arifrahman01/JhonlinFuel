<?php

namespace App\Livewire;

use App\Models\Receipt;
use Livewire\Component;
use Livewire\WithPagination;

class ReceiptPostingList extends Component
{
    use WithPagination;
    public $dateFilter;

    protected $paginationTheme = 'bootstrap';
    
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
