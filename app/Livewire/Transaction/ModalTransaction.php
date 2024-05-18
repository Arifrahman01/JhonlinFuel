<?php

namespace App\Livewire\Transaction;

use App\Imports\TransactionImport;
use App\Models\Transaction\TmpTransaction;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ModalTransaction extends Component
{
    use WithFileUploads;    
    
    public $loading = false;
    public $fileLoader;
    public $tess = 'Tes'; // kalo ini hilang file null terus
    public $users;
    protected $listeners = ['openModal'];

    public function openModal()
    {
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.transaction.modal-transaction');
    }

    public function store()
    {
        try {
            $filePath = $this->fileLoader->store('temp');
            Excel::import(new TransactionImport, $filePath);
            $this->dispatch('success', 'Loader is successfull');
            $this->closeModal();
            $this->dispatch('closeModal');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
    public function closeModal()
    {
        $this->tess= 'Tes';
        $this->loading = true;
    }
}
