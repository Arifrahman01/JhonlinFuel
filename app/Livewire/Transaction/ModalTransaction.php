<?php

namespace App\Livewire\Transaction;

use App\Imports\TransactionImport;
use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ModalTransaction extends Component
{
    use WithFileUploads;    
    
    public $loading = false;
    public $statusModal = 'uplaod';
    public $fileLoader;

    public $users;
    public $companiesModal;
    protected $listeners = ['openUpload','openEdit'];

    public function mount()
    {
        $this->loading = true;
    }
    public function openUpload()
    {
        $this->statusModal = 'upload';
        $this->loading = false;
    }
    public function openEdit()
    {
        $this->statusModal = 'edit';
        $this->companiesModal = Company::all();
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
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
    public function closeModal()
    {
        $this->loading = true;
    }
}
