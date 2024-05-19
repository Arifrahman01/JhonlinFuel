<?php

namespace App\Livewire\Transaction;

use App\Models\Transaction\TmpTransaction;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionList extends Component
{
    use WithPagination;
    public $filter_date;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];


    public function mount()
    {
        $this->filter_date = $this->filter_date ?? date('Y-m-d');
    }

    public function render()
    {
        $transactions = TmpTransaction::sumQty2($this->filter_date);
        return view('livewire.transaction.transaction-list', ['transactions' => $transactions]);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $transaction = TmpTransaction::findOrFail($id);
            $transaction->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }


}
