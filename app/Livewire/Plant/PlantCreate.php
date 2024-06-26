<?php

namespace App\Livewire\Plant;

use App\Models\Company;
use App\Models\Plant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

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
        $permissions = [
            'view-master-plant',
            'create-master-plant',
            'edit-master-plant',
            'delete-master-plant',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $companies = Company::allowed('view-master-plant')->get();
        return view('livewire.plant.plant-create', compact('companies'));
    }

    public function closeModal()
    {
        $this->loading = true;
    }

    public function openCreate($id = null)
    {
        $permissions = [
            'create-master-plant',
            'edit-master-plant',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
            $this->plantCodeReadOnly = false;
            $this->selectedCompany = null;
            $this->plantId = null;
            $this->plantCode = null;
            $this->plantName = null;
        }
        $this->loading = false;
    }

    public function store()
    {
        $permissions = [
            'create-master-plant',
            'edit-master-plant',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
                if ($plant->plant_code != $this->plantCode && $plant->hasDataByCode()) {
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
