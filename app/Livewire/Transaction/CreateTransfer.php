<?php

namespace App\Livewire\Transaction;

use App\Imports\TransferImport;
use App\Models\Company;
use App\Models\Material\Material;
use App\Models\Plant;
use App\Models\Sloc;
use App\Models\Transfer;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class CreateTransfer extends Component
{
    use WithFileUploads;

    public $loading = false;
    public $statusModal = 'uplaod';
    public $fileLoader;
    public $dataTransfer;

    public $companiesFrom = [];
    public $companiesTo = [];
    public $slocsFrom = [];
    public $slocsTo = [];
    public $materials = [];

    public $id;
    public $selectedFromCompany, $selectedToCompany;
    public $trans_date, $transportir, $material_code, $qty, $from_warehouse, $to_warehouse;

    protected $listeners = ['openUpload', 'openCreate'];

    public function mount()
    {
        $this->loading = true;
        $this->fileLoader = null;
        $this->materials = Material::all();
        $this->companiesFrom = Company::allowed('create-loader-transfer')->get();
        $this->companiesTo = Company::all();
    }

    public function updatedSelectedFromCompany($value)
    {
        $this->slocsFrom = Sloc::where('company_id', Company::where('company_code', $value)->value('id'))->get();
    }
    public function updatedSelectedToCompany($value)
    {
        $this->slocsTo = Sloc::where('company_id', Company::where('company_code', $value)->value('id'))->get();
    }

    public function openUpload()
    {
        $this->statusModal = 'upload';
        $this->loading = false;
    }
    public function openCreate($id = null)
    {
        if ($id) {
            $this->dataTransfer =  Transfer::find($id);
            $this->trans_type = $this->dataTransfer['trans_type'];
            $this->trans_date = $this->dataTransfer['trans_date'];
            $this->selectedFromCompany = $this->dataTransfer['from_company_code'];
            $this->from_warehouse = $this->dataTransfer['from_warehouse'];
            $this->selectedToCompany = $this->dataTransfer['to_company_code'];
            $this->to_warehouse = $this->dataTransfer['to_warehouse'];
            $this->transportir = $this->dataTransfer['transportir'];
            $this->material_code = $this->dataTransfer['material_code'];
            $this->qty = str_replace('.00', '', $this->dataTransfer['qty']);

            $this->slocsFrom = Sloc::where('company_id', Company::where('company_code',  $this->dataTransfer['from_company_code'])->value('id'))->get();
            $this->slocsTo = Sloc::where('company_id', Company::where('company_code',  $this->dataTransfer['to_company_code'])->value('id'))->get();
        } else {
            $this->dataTransfer = null;
            $this->trans_type = null;
            $this->trans_date = null;
            $this->selectedFromCompany = null;
            $this->from_company_code = null;
            $this->selectedToCompany = null;
            $this->from_warehouse  = null;
            $this->to_company_code = null;
            $this->to_warehouse  = null;
            $this->transportir  = null;
            $this->material_code  = null;
            $this->qty  = null;
        }
        $this->statusModal = 'edit';
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.transaction.create-transfer');
    }
    public function storeData($id = null)
    {
        try {
            $this->validate([
                'selectedFromCompany' => 'required',
                'selectedToCompany' => 'required',
                'from_warehouse' => 'required',
                'to_warehouse' => 'required',
                'trans_date'    => 'required',
                'transportir'   => 'required',
                'material_code' => 'required',
                'qty'   => 'required|int'
            ]);
            if ($id) {
                $tmpTrans = Transfer::find($id);
                $tmpTrans->update([
                    'trans_type'    => 'TRF',
                    'trans_date'    => $this->trans_date,
                    'from_company_code' => $this->selectedFromCompany,
                    'from_warehouse' => $this->from_warehouse,
                    'to_company_code' => $this->selectedToCompany,
                    'to_warehouse'  => $this->to_warehouse,
                    'transportir'   => $this->transportir,
                    'material_code' => $this->material_code,
                    'uom'           => 'L',
                    'qty'           => $this->qty,
                ]);
                $this->dispatch('success', 'Data has been updated');
            } else {
                Transfer::create([
                    'trans_type'    => 'TRF',
                    'trans_date'    => $this->trans_date,
                    'from_company_code' => $this->selectedFromCompany,
                    'from_warehouse' => $this->from_warehouse,
                    'to_company_code' => $this->selectedToCompany,
                    'to_warehouse'  => $this->to_warehouse,
                    'transportir'   => $this->transportir,
                    'material_code' => $this->material_code,
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
            } else {
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
