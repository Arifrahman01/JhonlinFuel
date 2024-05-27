<?php

namespace App\Livewire\Warehouse;

use App\Models\Company;
use App\Models\Material\Material;
use App\Models\Material\MaterialStock;
use App\Models\Plant;
use App\Models\Sloc;
use App\Models\Uom;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class WarehouseCreate extends Component
{
    public $loading = false;
    public $statusModal = 'Create';
    public $selectedCompany;
    public $plants = [];
    public $selectedPlant;
    public $warehouseCodeReadOnly = false;
    public $warehouseId;
    public $warehouseCode;
    public $warehouseName;
    protected $listeners = ['openCreate'];

    public function render()
    {
        $companies = Company::all();
        return view('livewire.warehouse.warehouse-create', compact('companies'));
    }

    public function closeModal()
    {
        $this->loading = false;
    }

    public function openCreate($id = null)
    {
        $this->loading = true;
        if ($id) {
            $this->statusModal = 'Edit';
            $warehouse = Sloc::find($id);
            $this->warehouseCodeReadOnly = $warehouse->hasDataByCode();
            $this->selectedCompany = $warehouse->company_id;
            $this->warehouseId = $id;
            $this->warehouseCode = $warehouse->sloc_code;
            $this->warehouseName = $warehouse->sloc_name;
        } else {
            $this->statusModal = 'Create';
            $this->selectedCompany = null;
            $this->warehouseId = null;
            $this->warehouseCode = null;
            $this->warehouseName = null;
        }

        $this->loading = false;
    }

    public function updatedSelectedCompany($value)
    {
        $this->plants = Plant::where('company_id', $value)
            ->get();
    }

    public function store()
    {
        DB::beginTransaction();
        try {

            if ($this->warehouseId) {
                $this->validate([
                    'selectedCompany' => 'required',
                    'selectedPlant' => 'required',
                    'warehouseCode' => [
                        'required',
                        Rule::unique('storage_locations', 'sloc_code')->ignore($this->warehouseId),
                    ],
                    'warehouseName' => 'required',
                ]);
                $warehouse = Sloc::find($this->warehouseId);
                if ($warehouse->hasDataByCode()) {
                    throw new \Exception("Warehouse Code has data. Can't be edited");
                }
                $warehouse->update([
                    'company_id' => $this->selectedCompany,
                    'plant_id' => $this->selectedPlant,
                    'sloc_code' => $this->warehouseCode,
                    'sloc_name' => $this->warehouseName,
                ]);
            } else {
                $this->validate([
                    'selectedCompany' => 'required',
                    'selectedPlant' => 'required',
                    'warehouseCode' => 'required|unique:storage_locations,sloc_code',
                    'warehouseName' => 'required',
                ]);
                $sloc = Sloc::create([
                    'company_id' => $this->selectedCompany,
                    'plant_id' => $this->selectedPlant,
                    'sloc_code' => $this->warehouseCode,
                    'sloc_name' => $this->warehouseName,
                ]);

                $material = Material::first();
                $uom = Uom::first();

                MaterialStock::create([
                    'company_id' => $this->selectedCompany,
                    'plant_id' => $this->selectedPlant,
                    'sloc_id' => $sloc->id,
                    'material_id' => $material->id,
                    'material_code' => $material->material_code,
                    'part_no' => $material->part_no,
                    'material_mnemonic' => $material->material_mnemonic,
                    'material_description' => $material->material_description,
                    'uom_id' => $uom->id,
                    'qty_soh' => 0,
                    'qty_intransit' => 0,
                ]);
            }
            DB::commit();
            $this->dispatch('success', $this->warehouseId ? 'Data has been updated' : 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
