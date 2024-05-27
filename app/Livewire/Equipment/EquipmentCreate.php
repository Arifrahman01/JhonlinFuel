<?php

namespace App\Livewire\Equipment;

use App\Models\Company;
use App\Models\Equipment;
use App\Models\Plant;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EquipmentCreate extends Component
{
    public $loading = false;
    public $statusModal = 'Create';
    public $plant, $equipmentNo, $equipmentDesc;
    public $selectedCompany;
    public $equipmentId;
    public $plants = [];

    protected $listeners = ['openCreate'];

    public Function mount()
    {
        $this->loading = true;
    }
    public function render()
    {
        $companies = Company::all();
        return view('livewire.equipment.equipment-create', ['companies' => $companies]);
    }
    public function closeModal()
    {
        $this->loading = true;
    }
    public function openCreate($id = null)
    {
        if ($id) {
            $this->statusModal = 'Edit';
            $this->equipmentId = $id;
            $equipment = Equipment::find($id);
            $this->selectedCompany = $equipment->company_id;
            $this->plants = Plant::where('company_id', $this->selectedCompany)->get();
            $this->plant = $equipment->plant_id;
            $this->equipmentNo = $equipment->equipment_no;
            $this->equipmentDesc = $equipment->equipment_description;
        }else{
            $this->equipmentId = null;
            $this->statusModal = 'Create';
            $this->selectedCompany = null;
            $this->plant = null;
            $this->equipmentNo = null;
            $this->equipmentDesc = null;
        }
        $this->loading = false;
    }

    public function updatedSelectedCompany($value)
    {
        $this->plants = Plant::where('company_id', $value)->get();
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            if ($this->equipmentId) {
                $this->validate([
                    'selectedCompany' => 'required|exists:companies,id',
                    'plant' => 'required|exists:plants,id',
                    'equipmentNo' => 'required|string|max:255',
                    'equipmentDesc' => 'required|string|max:255',
                ]);
                $equipment = Equipment::find($this->equipmentId);
                $equipment->update([
                    'company_id' => $this->selectedCompany,
                    'plant_id' => $this->plant,
                    'equipment_no' => $this->equipmentNo,
                    'equipment_description' => $this->equipmentDesc,
                ]);

            } else {
                $this->validate([
                    'selectedCompany' => 'required|exists:companies,id',
                    'plant' => 'required|exists:plants,id',
                    'equipmentNo' => 'required|string|max:255|unique:equipments,equipment_no',
                    'equipmentDesc' => 'required|string|max:255',
                ]);
                Equipment::create([
                    'company_id' => $this->selectedCompany,
                    'plant_id' => $this->plant,
                    'equipment_no' => $this->equipmentNo,
                    'equipment_description' => $this->equipmentDesc,
                ]);
            }
            DB::commit();
            $this->dispatch('success', $this->equipmentId ? 'Data has been updated' : 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
