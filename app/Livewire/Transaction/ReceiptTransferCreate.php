<?php

namespace App\Livewire\Transaction;

use App\Imports\ReceiptTransferImport;
use App\Models\Company;
use App\Models\Material\Material;
use App\Models\ReceiptTransfer;
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

    public $rcvId = null;

    public $transDate;

    public $fromSlocs = [];
    public $selectedFromWarehouse;
    public $companies = [];
    public $selectedToCompany;
    public $isLoadingFromWarehouse = false;

    public $toSlocs = [];
    public $selectedToWarehouse;
    public $isLoadingToWarehouse = false;

    public $transportir;
    public $materials;
    public $selectedMaterial;

    public $qty;

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

    public function openCreate($id = null)
    {
        if ($id) {
            $this->statusModal = 'Edit';
            $this->rcvId = $id;
            $rcvTransfer = ReceiptTransfer::find($id);
            $this->transDate = $rcvTransfer->trans_date;
            $this->selectedFromWarehouse = $rcvTransfer->from_warehouse;
            $this->selectedToCompany = $rcvTransfer->to_company_code;
            $companyId = Company::where('company_code', $rcvTransfer->to_company_code)
                ->value('id');
            $this->toSlocs = Sloc::where('company_id', $companyId)
                ->get();
            $this->selectedToWarehouse = $rcvTransfer->to_warehouse;
            $this->transportir = $rcvTransfer->transportir;
            $this->selectedMaterial = $rcvTransfer->material_code;
            $this->qty = intval($rcvTransfer->qty);
        } else {

            $this->statusModal = 'Create';
            $this->rcvId = null;

            $this->transDate = null;
            $this->selectedFromWarehouse = null;
            $this->selectedToCompany = null;
            $this->selectedToWarehouse = null;
            $this->transportir = null;
            $this->selectedMaterial = null;
            $this->qty = null;
        }
        $this->loading = false;
    }

    public function openUpload()
    {
        $this->statusModal = 'upload';
        $this->fileLoader = null;
        $this->loading = false;
    }

    public function render()
    {
        $this->companies = Company::all();
        $this->materials = Material::all();
        $userCompanyId = auth()->user()->company_id;
        $this->fromSlocs = Sloc::all();
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

    public function storeData($id = null)
    {
        try {
            $userCompanyCode = Company::find(auth()->user()->company_id)->company_code;
            $this->validate([
                'transDate'      => 'required',
                'selectedFromWarehouse'  => 'required',
                'selectedToCompany' => 'required',
                'selectedToWarehouse'    => 'required',
                'transportir'     => 'required',
                'selectedMaterial'   => 'required',
                'qty'             => 'required|int'
            ]);
            if ($this->rcvId) {
                ReceiptTransfer::find($this->rcvId)
                    ->update([
                        'trans_type'    => 'RCT',
                        'trans_date'    => $this->transDate,
                        'from_company_code' => $userCompanyCode,
                        'from_warehouse' => $this->selectedFromWarehouse,
                        'to_company_code' => $this->selectedToCompany,
                        'to_warehouse'     => $this->selectedToWarehouse,
                        'transportir'    => $this->transportir,
                        'material_code' => $this->selectedMaterial,
                        'uom'           => 'L',
                        'qty'           => $this->qty,
                    ]);
                $this->dispatch('success', 'Data has been updated');
            } else {
                ReceiptTransfer::create([
                    'trans_type'    => 'RCT',
                    'trans_date'    => $this->transDate,
                    'from_company_code' => $userCompanyCode,
                    'from_warehouse' => $this->selectedFromWarehouse,
                    'to_company_code' => $this->selectedToCompany,
                    'to_warehouse'     => $this->selectedToWarehouse,
                    'transportir'    => $this->transportir,
                    'material_code' => $this->selectedMaterial,
                    'uom'           => 'L',
                    'qty'           => $this->qty,
                ]);
                $this->dispatch('success', 'Data has been created');
            }

            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
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
