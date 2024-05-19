<?php

namespace App\Livewire\Transaction;

use App\Imports\TransactionImport;
use App\Models\Company;
use App\Models\Material\Material;
use App\Models\Transaction\TmpTransaction;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ModalTransaction extends Component
{
    use WithFileUploads;    
    
    public $loading = false;
    public $statusModal = 'uplaod';
    public $fileLoader;

    public $dataTmp;
    public $companiesModal;
    public $material;

    public $company_code,$trans_type,$fuelType,$qty,$meter_value;
    protected $listeners = ['openUpload','openEdit'];

    public function mount()
    {
        $this->loading = true;
        $this->companiesModal = Company::all();
        $this->material = Material::all();
    }
    public function openUpload()
    {
        $this->statusModal = 'upload';
        $this->loading = false;
    }
    public function openEdit($id=null)
    {
        if ($id) {
            $this->dataTmp =  TmpTransaction::find($id)->first();
            $this->company_code = $this->dataTmp['company_code'];
            $this->trans_type = $this->dataTmp['trans_type'];
            $this->fuelType = $this->dataTmp['fuel_type'];
            $this->qty = $this->dataTmp['qty'];
            $this->meter_value = $this->dataTmp['meter_value'];
        }else{
            $this->dataTmp = null;
            $this->company_code = null;
            $this->trans_type = null;
            $this->fuelType = null;
            $this->qty = null;
            $this->meter_value = null;
        }
        $this->statusModal = 'edit';
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
    public function storeData($id = null)
    {
        // $this->company_code 
        // $this->full_warehouse
        try {
            $this->validate([
                'company_code' => 'required',
                'fuel_warehouse' => 'required',
                'trans_type'    => 'required',
                'trans_date'    => 'required',
                'fuelman'       => 'required',
                'equipment_no'  => 'required',
                'location'      => 'required',
                'department'    => 'required',
                'activity'      => 'required',
                'fuel_type'     => 'required',
                'qty'           => 'required',
                'statistic_type'=> 'required',
                'meter_value'   => 'required'
            ]);
            if ($id) {
                
                $this->dispatch('success', 'Data has been updated');
            } else {
               
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
        $this->loading = true;
    }
}
