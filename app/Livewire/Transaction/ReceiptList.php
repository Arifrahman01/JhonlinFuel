<?php

namespace App\Livewire\Transaction;

use Livewire\Component;

class ReceiptList extends Component
{
    public function render()
    {
        return view('livewire.transaction.receipt-list');
    }
    public function search()
    {
        $this->resetPage();
    }
}
