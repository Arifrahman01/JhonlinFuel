<?php

namespace App\Livewire\Fuelman;

use App\Models\Company;
use App\Models\Fuelman;
use App\Models\Plant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

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
        $companies = Company::all();
        return view('livewire.fuelman.fuelman-create', compact('companies'));
    }

    public function closeModal()
    {
        $this->loading = false;
    }

    public function updatedSelectedCompany($value)
    {
        $this->plants = Plant::where('company_id', $value)
            ->get();
    }

    public function openCreate($id = null)
    {
        $this->loading = true;
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
                if ($fuelman->hasDataByNik()) {
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
