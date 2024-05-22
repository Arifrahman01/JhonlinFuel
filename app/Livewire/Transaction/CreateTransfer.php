<?php

namespace App\Livewire\Transaction;

use App\Imports\TransferImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class CreateTransfer extends Component
{
    use WithFileUploads;

    public $loading = false;
    public $statusModal = 'uplaod';
    public $fileLoader;

    public $companies = [];
    public $materials = [];
    public $slocs = [];
    public $plants = [];

    public $id;
    public $dataTransfer  ;

    protected $listeners = ['openUpload','openCreate'];

    public function mount()
    {
        $this->loading = true;
        $this->fileLoader = null;
    }
    public function openUpload()
    {
        $this->statusModal = 'upload';
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.transaction.create-transfer');
    }
    public function storeLoader()
    {
        try {
            if ($this->fileLoader) {
                $file = $this->fileLoader;
                $filePath = $this->fileLoader->store('temp');
                Excel::import(new TransferImport, $filePath);
                $this->dispatch('success', 'Loader is successfull');
                $this->closeModal();
                $this->dispatch('closeModal');
                $this->dispatch('refreshPage');
            }else{
                $this->dispatch('error', 'File not uploaded');
            }
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
    public function closeModal()
    {
        $this->loading = true;
        $this->dataTransfer = null;
        $this->id = null;
    }
}
