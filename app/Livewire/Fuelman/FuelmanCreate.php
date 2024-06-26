<?php

namespace App\Livewire\Fuelman;

use App\Models\Company;
use App\Models\Fuelman;
use App\Models\Plant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class FuelmanCreate extends Component
{
    public $loading = false;
    public $statusModal = 'Create';
    public $selectedCompany;
    public $plants = [];
    public $selectedPlant;
    public $fuelmanNIKReadOnly = false;
    public $fuelmanId;
    public $fuelmanNIK;
    public $fuelmanName;
    protected $listeners = ['openCreate'];
    public function render()
    {
        $permissions = [
            'view-master-fuelman',
            'create-master-fuelman',
            'edit-master-fuelman',
            'delete-master-fuelman',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $companies = Company::allowed('view-master-fuelman')->get();
        return view('livewire.fuelman.fuelman-create', compact('companies'));
    }

    public function closeModal()
    {
        $this->loading = true;
    }

    public function updatedSelectedCompany($value)
    {
        $this->plants = Plant::where('company_id', $value)
            ->get();
    }

    public function openCreate($id = null)
    {
        $permissions = [
            'create-master-fuelman',
            'edit-master-fuelman',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($id) {
            $this->statusModal = 'Edit';
            $fuelman = Fuelman::find($id);
            $this->fuelmanNIKReadOnly = $fuelman->hasDataByNik();
            $this->selectedCompany = $fuelman->company_id;
            $this->plants = Plant::where('company_id', $this->selectedCompany)
                ->get();
            $this->selectedPlant = $fuelman->plant_id;
            $this->fuelmanId = $id;
            $this->fuelmanNIK = $fuelman->nik;
            $this->fuelmanName = $fuelman->name;
        } else {
            $this->statusModal = 'Create';
            $this->fuelmanNIKReadOnly = false;
            $this->selectedCompany = null;
            $this->selectedPlant = null;
            $this->fuelmanId = null;
            $this->fuelmanNIK = null;
            $this->fuelmanName = null;
        }

        $this->loading = false;
    }

    public function store()
    {
        $permissions = [
            'create-master-fuelman',
            'edit-master-fuelman',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();
        try {

            if ($this->fuelmanId) {
                $this->validate([
                    'selectedCompany' => 'required',
                    'selectedPlant' => 'required',
                    'fuelmanNIK' => [
                        'required',
                        Rule::unique('fuelmans', 'nik')->ignore($this->fuelmanId),
                    ],
                    'fuelmanName' => 'required',
                ]);
                $fuelman = Fuelman::find($this->fuelmanId);
                if ($fuelman->nik != $this->fuelmanNIK && $fuelman->hasDataByNik()) {
                    throw new \Exception("Fuelman NIK has data. Can't be edited");
                }
                $fuelman->update([
                    'company_id' => $this->selectedCompany,
                    'plant_id' => $this->selectedPlant,
                    'nik' => $this->fuelmanNIK,
                    'name' => $this->fuelmanName,
                ]);
            } else {
                $this->validate([
                    'selectedCompany' => 'required',
                    'selectedPlant' => 'required',
                    'fuelmanNIK' => 'required|unique:fuelmans,nik',
                    'fuelmanName' => 'required',
                ]);
                $fuelman = Fuelman::create([
                    'company_id' => $this->selectedCompany,
                    'plant_id' => $this->selectedPlant,
                    'nik' => $this->fuelmanNIK,
                    'name' => $this->fuelmanName,
                ]);
            }
            DB::commit();
            $this->dispatch('success', $this->fuelmanId ? 'Data has been updated' : 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
