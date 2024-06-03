<?php

namespace App\Livewire\Transaction;

use App\Imports\ReceiptImport;
use App\Imports\ReceivedImport;
use App\Models\Company;
use App\Models\Material\Material;
use App\Models\Plant;
use App\Models\Receipt;
use App\Models\Sloc;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class CreateReceipt extends Component
{
    use WithFileUploads;

    public $loading = false;
    public $statusModal = 'uplaod';
    public $fileLoader;
    public $dataReceipt;

    public $companies = [];
    public $materials = [];
    public $slocs = [];
    public $plants = [];

    public $id;
    public $dataReceived;

    public $selectedCompany, $selectedlocation, $warehouse, $trans_date, $po_no, $do_no, $transportir, $material_code, $qty;

    protected $listeners = ['openUpload', 'openCreate'];

    public function render()
    {
        return view('livewire.transaction.create-receipt');
    }
    public function mount()
    {
        $this->loading = true;
        $this->fileLoader = null;
        $this->companies = Company::allowed('create-loader-receipt-po')->get();
        $this->materials = Material::all();
    }
    public function openUpload()
    {
        $this->statusModal = 'upload';
        $this->loading = false;
    }
    public function openCreate($id = null)
    {
        if ($id) {
            $this->dataReceipt =  Receipt::find($id);
            $this->selectedCompany = $this->dataReceipt['company_code'];
            $this->selectedlocation = $this->dataReceipt['location'];
            $this->trans_date = $this->dataReceipt['trans_date'];
            $this->po_no = $this->dataReceipt['po_no'];
            $this->do_no = $this->dataReceipt['do_no'];
            $this->transportir = $this->dataReceipt['transportir'];
            $this->material_code = $this->dataReceipt['material_code'];
            $this->qty = str_replace('.00', '', $this->dataReceipt['qty']);
            $this->plants = Plant::where('company_id', Company::where('company_code',  $this->dataReceipt['company_code'])->value('id'))->get();
            $this->slocs = Sloc::where('plant_id', Plant::where('plant_code',  $this->dataReceipt['location'])->value('id'))->get();
        } else {
            $this->dataReceipt = null;
            $this->selectedCompany = null;
            $this->selectedlocation = null;
            $this->trans_date = null;
            $this->po_no = null;
            $this->do_no = null;
            $this->transportir = null;
            $this->material_code = null;
            $this->qty = null;
            $this->plants = [];
            $this->slocs = [];
        }
        $this->statusModal = 'edit';
        $this->loading = false;
    }

    public function updatedSelectedCompany($value)
    {
        $this->plants = Plant::where('company_id', Company::where('company_code', $value)->value('id'))->get();
    }

    public function updatedSelectedlocation($value)
    {
        $this->slocs = Sloc::where('plant_id',  Plant::where('plant_code', $value)->value('id'))->get();
    }

    public function storeData($id = null)
    {
        try {
            $this->validate([
                'selectedCompany' => 'required',
                'selectedlocation' => 'required',
                'warehouse' => 'required',
                'trans_date'    => 'required',
                'po_no'         => 'required',
                'do_no'         => 'required',
                'transportir'  => 'required',
                'material_code' => 'required',
                'qty'   => 'required|int'
            ]);
            if ($id) {
                $tmpTrans = Receipt::find($id);
                $tmpTrans->update([
                    'company_code'  => $this->selectedCompany,
                    'location'      => $this->selectedlocation,
                    'trans_type'    => 'RCV',
                    'warehouse'     => $this->warehouse,
                    'trans_date'    => $this->trans_date,
                    'po_no'         => $this->po_no,
                    'do_no'         => $this->do_no,
                    'transportir'   => $this->transportir,
                    'material_code' => $this->material_code,
                    'qty'           => $this->qty,
                    'uom'           => 'L',
                ]);
                $this->dispatch('success', 'Data has been updated');
            } else {
                Receipt::create([
                    'company_code'  => $this->selectedCompany,
                    'location'      => $this->selectedlocation,
                    'trans_type'    => 'RCV',
                    'warehouse'     => $this->warehouse,
                    'trans_date'    => $this->trans_date,
                    'po_no'         => $this->po_no,
                    'do_no'         => $this->do_no,
                    'transportir'   => $this->transportir,
                    'material_code' => $this->material_code,
                    'qty'           => $this->qty,
                    'uom'           => 'L',
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
                Excel::import(new ReceiptImport, $filePath);
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
        $this->dataReceived = null;
        $this->id = null;
    }
}
