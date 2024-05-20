<?php

namespace App\Livewire\Transaction;

use App\Imports\TransactionImport;
use App\Models\Activity;
use App\Models\Company;
use App\Models\Equipment;
use App\Models\Material\Material;
use App\Models\Plant;
use App\Models\Sloc;
use App\Models\Transaction\TmpTransaction;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ModalTransaction extends Component
{
    use WithFileUploads;    
    
    public $loading = false;
    public $statusModal = 'uplaod';
    public $fileLoader;

    public $id;
    public $dataTmp;
    public $companiesModal;
    public $material;
    public $slocs;
    public $equipments;
    public $plants;
    public $activitys;

    public $company_code , $fuel_warehouse , $trans_date , $fuelman , $equipment_no , $location , $department , $activity ,$trans_type , $fuel_type , $qty , $statistic_type , $meter_value;
    protected $listeners = ['openUpload','openEdit'];

    
    public function mount()
    {
        $this->loading = true;
        $this->fileLoader = null;
        $this->companiesModal = Company::all();
        $this->material = Material::all();
        $this->slocs = Sloc::all();
        $this->equipments = Equipment::all();
        $this->plants = Plant::all();
        $this->activitys = Activity::all();
        // dd($this->equipments);

        if ($this->id) {
            $this->dataTmp =  TmpTransaction::find($this->id)->first();
            $this->company_code = $this->dataTmp['company_code'];
            $this->trans_type = $this->dataTmp['trans_type'];
            $this->fuel_type = $this->dataTmp['fuel_type'];
            $this->qty = $this->dataTmp['qty'];
            $this->meter_value = $this->dataTmp['meter_value'];
            $this->fuel_warehouse = $this->dataTmp['fuel_warehouse'];
            $this->trans_date = $this->dataTmp['trans_date'];
            $this->fuelman = $this->dataTmp['fuelman'];
            $this->equipment_no = $this->dataTmp['equipment_no'];
            $this->location = $this->dataTmp['location'];
            $this->department = $this->dataTmp['department'];
            $this->activity = $this->dataTmp['activity'];
            $this->statistic_type = $this->dataTmp['statistic_type'];
        }else{
            $this->dataTmp = null;
            $this->company_code = null;
            $this->trans_type = null;
            $this->fuel_type = null;
            $this->qty = null;
            $this->meter_value = null;
            $this->fuel_warehouse = null;
            $this->trans_date = null;
            $this->fuelman = null;
            $this->equipment_no = null;
            $this->location = null;
            $this->department = null;
            $this->activity = null;
            $this->statistic_type = null;
        }
    }
    public function openUpload()
    {
        $this->statusModal = 'upload';
        $this->loading = false;
    }
    public function openEdit($id=null)
    {
        $this->loading = true;
        if ($id) {
            $this->dataTmp =  TmpTransaction::find($id);
            $this->company_code = $this->dataTmp['company_code'];
            $this->trans_type = $this->dataTmp['trans_type'];
            $this->fuel_type = $this->dataTmp['fuel_type'];
            $this->qty = $this->dataTmp['qty'];
            $this->meter_value = $this->dataTmp['meter_value'];
            $this->fuel_warehouse = $this->dataTmp['fuel_warehouse'];
            $this->trans_date = $this->dataTmp['trans_date'];
            $this->fuelman = $this->dataTmp['fuelman'];
            $this->equipment_no = $this->dataTmp['equipment_no'];
            $this->location = $this->dataTmp['location'];
            $this->department = $this->dataTmp['department'];
            $this->activity = $this->dataTmp['activity'];
            $this->statistic_type = $this->dataTmp['statistic_type'];
        }else{
            $this->dataTmp = null;
            $this->company_code = null;
            $this->trans_type = null;
            $this->fuel_type = null;
            $this->qty = null;
            $this->meter_value = null;
            $this->fuel_warehouse = null;
            $this->trans_date = null;
            $this->fuelman = null;
            $this->equipment_no = null;
            $this->location = null;
            $this->department = null;
            $this->activity = null;
            $this->statistic_type = null;
        }
        $this->statusModal = 'edit';
        $this->loading = false;
    }
    public function render()
    {
        return view('livewire.transaction.modal-transaction');
    }
    public function updatedFileLoader()
    {
        if ($this->fileLoader) {
            $this->validate([
                'fileLoader' => 'required|file|mimes:xlsx,xls',
            ]);
        }
    }
    public function store()
    {
        try {
            if ($this->fileLoader) {
                $file = $this->fileLoader;
                $filePath = $this->fileLoader->store('temp');
                Excel::import(new TransactionImport, $filePath);
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
    public function storeData($id = null)
    {
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
                $tmpTrans = TmpTransaction::find($id);
                $tmpTrans->update([
                    'company_code'  => $this->company_code,
                    'fuel_warehouse'=> $this->fuel_warehouse,
                    'trans_type'    => $this->trans_type,
                    'trans_date'    => $this->trans_date,
                    'fuelman'       => $this->fuelman,
                    'equipment_no'  => $this->equipment_no,
                    'location'      => $this->location,
                    'department'    => $this->department,
                    'activity'      => $this->activity,
                    'fuel_type'     => $this->fuel_type,
                    'qty'           => $this->qty,
                    'statistic_type'=> $this->statistic_type,
                    'meter_value'   => $this->meter_value,
                    'status_error'  => null,          
                ]);
                $this->dispatch('success', 'Data has been updated');
            } else {
                TmpTransaction::create([
                    'company_code'  => $this->company_code,
                    'fuel_warehouse'=> $this->fuel_warehouse,
                    'trans_type'    => $this->trans_type,
                    'trans_date'    => $this->trans_date,
                    'fuelman'       => $this->fuelman,
                    'equipment_no'  => $this->equipment_no,
                    'location'      => $this->location,
                    'department'    => $this->department,
                    'activity'      => $this->activity,
                    'fuel_type'     => $this->fuel_type,
                    'qty'           => $this->qty,
                    'statistic_type'=> $this->statistic_type,
                    'meter_value'   => $this->meter_value,
                    'status_error'  => null,
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
        $this->loading = true;
        $this->tmpData = null;
        $this->id = null;
    }
}
