<?php

namespace App\Livewire\Transaction;

use App\Models\User;
use Livewire\Component;

class TransactionList extends Component
{

    public function render()
    {
        $transaction = User::with('role')->paginate(10);
        return view('livewire.transaction.transaction-list',compact('transaction'));
    }


}
