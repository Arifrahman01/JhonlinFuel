<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class PostingList extends Component
{
    use WithPagination;
    public $filter_date;

    public function mount()
    {
        $this->filter_date = $this->filter_date ?? date('Y-m-d');
    }

    public function render()
    {
        $transactions = Transaction::sumQty($this->filter_date);
        return view('livewire.transaction.posting-list', ['transactions' => $transactions]);
    }
    public function search()
    {
        $this->resetPage();
    }
}
