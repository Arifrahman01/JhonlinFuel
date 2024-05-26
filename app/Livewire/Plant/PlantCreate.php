<?php

namespace App\Livewire\Plant;

use App\Models\Company;
use App\Models\Plant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PlantCreate extends Component
{
    public $loading = false;
    public $statusModal = 'Create';
    public $selectedCompany;
    public $plantId;
    public $plantCode;
    public $plantCodeReadOnly = false;
    public $plantName;
    protected $listeners = ['openCreate'];
    public function render()
    {
        $companies = Company::all();
        return view('livewire.plant.plant-create', compact('companies'));
    }

    public function openModal()
    {
    }

    public function closeModal()
    {
        $this->loading = false;
    }

    public function openCreate($id = null)
    {
        $this->dispatch('logData', $id);
        if ($id) {
            $this->statusModal = 'Edit';
            $plant = Plant::find($id);
            $this->plantCodeReadOnly = $plant->hasDataByCode();
            $this->selectedCompany = $plant->company_id;
            $this->plantId = $id;
            $this->plantCode = $plant->plant_code;
            $this->plantName = $plant->plant_name;
        } else {
            $this->statusModal = 'Create';
            $this->selectedCompany = null;
            $this->plantId = null;
            $this->plantCode = null;
            $this->plantName = null;
        }

        $this->loading = false;
    }

    public function store()
    {
        DB::beginTransaction();
        try {

            if ($this->plantId) {
                $this->validate([
                    'plantCode' => [
                        'required',
                        Rule::unique('plants', 'plant_code')->ignore($this->plantId),
                    ],
                    'plantName' => 'required',
                ]);
                $plant = Plant::find($this->plantId);
                if ($plant->hasDataByCode()) {
                    throw new \Exception("Plant Code has data. Can't be edited");
                }
                $plant->update([
                    'company_id' => $this->selectedCompany,
                    'plant_code' => $this->plantCode,
                    'plant_name' => $this->plantName,
                ]);
            } else {
                $this->validate([
                    'plantCode' => 'required|unique:plants,plant_code',
                    'plantName' => 'required',
                ]);
                Plant::create([
                    'company_id' => $this->selectedCompany,
                    'plant_code' => $this->plantCode,
                    'plant_name' => $this->plantName,
                ]);
            }
            DB::commit();
            $this->dispatch('success', $this->plantId ? 'Data has been updated' : 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
