<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction\Transaction;
use Livewire\Component;
use Livewire\WithPagination;

class PostingList extends Component
{
    use WithPagination;
    public $start_date;
    public $end_date;

    public function mount()
    {
        $this->start_date = $this->start_date ?? date('Y-m-d', strtotime("-30 days"));
        $this->end_date = $this->end_date ?? date('Y-m-d');
    }

    public function render()
    {
        $transactions = Transaction::whereBetween('trans_date', [$this->start_date, $this->end_date])->orderBy('id','desc')->paginate(10);
        return view('livewire.transaction.posting-list', ['transactions' => $transactions]);
    }
    public function search()
    {
        $this->resetPage();
    }
}
