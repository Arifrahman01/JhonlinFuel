<?php

namespace App\Livewire\Transaction;

use App\Imports\IssueImport;
use App\Models\Activity;
use App\Models\Company;
use App\Models\Department;
use App\Models\Fuelman;
use App\Models\Issue;
use App\Models\Material\Material;
use App\Models\Plant;
use App\Models\Sloc;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class IssueCreate extends Component
{
    use WithFileUploads;

    public $loading = true;
    public $statusModal = 'uplaod';
    public $fileLoader;

    public $plants = [];
    public $slocs = [];
    public $departments = [];
    public $fuelmans = [];
    public $activitys = [];
    public $companies = [];
    public $materials = [];
    public $idIssue;
    public $issue;

    public $equipments;
    public $selectedCompany;
    public $selectedlocation;

    public $trans_type = 'ISS';
    public $warehouse, $trans_date, $fuelman, $equipment_no, $department, $activity, $material_code, $qty, $statistic_type, $meter_value;

    protected $listeners = ['openUpload', 'openEdit'];
    public function mount()
    {
        $this->companies = Company::allowed('create-loader-issue')->get();
        $this->materials = Material::all();
    }
    public function render()
    {
        return view('livewire.transaction.issue-create', ['companies' => $this->companies, 'materials' => $this->materials]);
    }
    public function openUpload()
    {
        $this->statusModal = 'upload';
        $this->loading = false;
    }



    public function updatedSelectedCompany($value)
    {
        $this->plants = Plant::where('company_id', Company::where('company_code', $value)->value('id'))->get();
        $this->departments = Department::where('company_id', Company::where('company_code', $value)->value('id'))->get();
        $this->activitys = Activity::where('company_id', Company::where('company_code', $value)->value('id'))->get();
    }
    public function updatedSelectedlocation($value)
    {
        $this->slocs = Sloc::where('plant_id', Plant::where('plant_code', $value)->value('id'))->get();
        $this->fuelmans = Fuelman::where('plant_id', Plant::where('plant_code', $value)->value('id'))->get();
    }

    public function openEdit($id = null)
    {
        $this->loading = true;
        if ($id) {
            $this->idIssue = $id;
            $this->issue =  Issue::find($id);
            $this->selectedCompany = $this->issue['company_code'];
            $this->trans_type = $this->issue['trans_type'];
            $this->material_code = $this->issue['material_code'];
            $this->qty = intval($this->issue['qty']);
            $this->meter_value = intval($this->issue['meter_value']);
            $this->warehouse = $this->issue['warehouse'];
            $this->trans_date = $this->issue['trans_date'];
            $this->fuelman = $this->issue['fuelman'];
            $this->equipment_no = $this->issue['equipment_no'];
            $this->selectedlocation = $this->issue['location'];
            $this->department = $this->issue['department'];
            $this->activity = $this->issue['activity'];
            $this->statistic_type = $this->issue['statistic_type'];
            $this->plants = Plant::where('company_id', Company::where('company_code',  $this->issue['company_code'])->value('id'))->get();
            $this->slocs = Sloc::where('plant_id', Plant::where('plant_code', $this->issue['location'])->value('id'))->get();
            $this->departments = Department::where('company_id', Company::where('company_code',  $this->issue['company_code'])->value('id'))->get();
            $this->fuelmans = Fuelman::where('plant_id',  Plant::where('plant_code',  $this->issue['location'])->value('id'))->get();
            $this->activitys = Activity::where('company_id', Company::where('company_code', $this->issue['company_code'])->value('id'))->get();
        } else {
            $this->dataTmp = null;
            $this->selectedCompany = null;
            $this->trans_type = 'ISS';
            $this->material_code = null;
            $this->qty = null;
            $this->meter_value = null;
            $this->warehouse = null;
            $this->trans_date = null;
            $this->fuelman = null;
            $this->equipment_no = null;
            $this->selectedlocation = null;
            $this->department = null;
            $this->activity = null;
            $this->statistic_type = null;
        }
        $this->statusModal = 'edit';
        $this->loading = false;
    }
    public function store()
    {
        try {
            if ($this->fileLoader) {
                $file = $this->fileLoader;
                $filePath = $this->fileLoader->store('temp');
                Excel::import(new IssueImport, $filePath);
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

    public function storeData()
    {
        try {
            $this->validate([
                'selectedCompany' => 'required',
                'warehouse' => 'required',
                'trans_type'    => 'required',
                'trans_date'    => 'required',
                'fuelman'       => 'required',
                'equipment_no'  => 'required',
                'selectedlocation' => 'required',
                'department'    => 'required',
                'activity'      => 'required',
                'material_code'     => 'required',
                'qty'           => 'required',
                'statistic_type' => 'required',
                'meter_value'   => 'required'
            ]);
            if ($this->idIssue) {
                $tmpTrans = Issue::find($this->idIssue);
                $tmpTrans->update([
                    'company_code'  => $this->selectedCompany,
                    'warehouse' => $this->warehouse,
                    'trans_type'    => $this->trans_type,
                    'trans_date'    => $this->trans_date,
                    'fuelman'       => $this->fuelman,
                    'equipment_no'  => $this->equipment_no,
                    'location'      => $this->selectedlocation,
                    'department'    => $this->department,
                    'activity'      => $this->activity,
                    'material_code'     => $this->material_code,
                    'qty'           => $this->qty,
                    'statistic_type' => $this->statistic_type,
                    'meter_value'   => $this->meter_value,
                    'error_status'  => null,
                    'uom'           => 'L'
                ]);
                $this->dispatch('success', 'Data has been updated');
            } else {
                Issue::create([
                    'company_code'  => $this->selectedCompany,
                    'warehouse' => $this->warehouse,
                    'trans_type'    => $this->trans_type,
                    'trans_date'    => $this->trans_date,
                    'fuelman'       => $this->fuelman,
                    'equipment_no'  => $this->equipment_no,
                    'location'      => $this->selectedlocation,
                    'department'    => $this->department,
                    'activity'      => $this->activity,
                    'material_code' => $this->material_code,
                    'qty'           => $this->qty,
                    'statistic_type' => $this->statistic_type,
                    'meter_value'   => $this->meter_value,
                    'status_error'  => null,
                    'uom'           => 'L'
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
        $this->id = null;
    }
}
