<?php

namespace App\Livewire\Transaction;

use App\Imports\ReceiptTransferImport;
use App\Models\Company;
use App\Models\Sloc;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ReceiptTransferCreate extends Component
{
    use WithFileUploads;

    public $loading = false;
    public $statusModal = 'upload';
    public $fileLoader;

    public $fromSlocs = [];
    public $selectedFromWarehouse;
    public $companies = [];
    public $selectedToCompany;
    public $isLoadingFromWarehouse = false;

    public $toSlocs = [];
    public $selectedToWarehouse;
    public $isLoadingToWarehouse = false;

    // public $companies = [];
    // public $plants = [];
    // public $slocs = [];
    // public $materials = [];

    // public $id;
    // public $dataTransfer;

    protected $listeners = ['openUpload', 'openCreate'];

    public function mount()
    {
        // $this->loading = true;
        $this->fileLoader = null;
    }

    public function openCreate()
    {
        $this->statusModal = 'create';
        $this->loading = false;
    }

    public function openUpload()
    {
        $this->statusModal = 'upload';
        $this->loading = false;
    }

    public function render()
    {
        $this->companies = Company::all();
        $userCompanyId = 1;
        $this->fromSlocs = Sloc::where('company_id', $userCompanyId)
            ->get();
        return view('livewire.transaction.receipt-transfer-create');
    }

    public function updatedSelectedToCompany($value)
    {
        $this->isLoadingToWarehouse = true;
        $companyId = Company::where('company_code', $value)
            ->value('id');
        $this->toSlocs = Sloc::where('company_id', $companyId)
            ->get();
        $this->isLoadingToWarehouse = false;
    }

    public function storeLoader()
    {
        try {
            if ($this->fileLoader) {
                $file = $this->fileLoader;
                $filePath = $this->fileLoader->store('temp');
                Excel::import(new ReceiptTransferImport, $filePath);
                $this->dispatch('success', 'Loader is successfull');
                $this->closeModal();
                $this->dispatch('closeModal');
                $this->dispatch('refreshPage');
            } else {
                $this->dispatch('error', 'File not uploaded');
            }
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function closeModal()
    {
        $this->loading = false;
        // $this->dataTransfer = null;
        // $this->id = null;
    }
}
