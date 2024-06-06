<?php

namespace App\Livewire\Warehouse;

use App\Models\Company;
use App\Models\Material\MaterialStock;
use App\Models\Plant;
use App\Models\Sloc;
use App\Models\SlocTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Exists;
use Livewire\Component;

class WarehouseTransferCreate extends Component
{
    public $loading = false;
    public $companies = [];
    public $fromPlants = [];
    public $toPlants = [];
    public $fromSlocs = [];
    public $toSlocs = [];

    public $selectedFromCompany, $selectedFromPlant, $from_warehouse;
    public $selectedToCompany, $selectedToPlant, $notes;

    public function mount()
    {
        $this->from_warehouse = null;

        $this->companies = Company::all();
    }

    public function updatedSelectedFromCompany($value)
    {
        $this->fromPlants = Plant::where('company_id', $value)->get();
    }

    public function updatedSelectedToCompany($value)
    {
        $this->toPlants = Plant::where('company_id', $value)->get();
    }

    public function updatedSelectedFromPlant($value)
    {
        $this->fromSlocs = Sloc::where('plant_id', $value)->get();
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-transfer-create');
    }

    public function store($id = null)
    {
        DB::beginTransaction();
        try {
            $this->validate([
                'selectedFromCompany' => 'required',
                'selectedFromPlant' => 'required',
                'from_warehouse' => 'required',
                'selectedToCompany' => 'required',
                'selectedToPlant'    => 'required',
            ]);

            $stock = MaterialStock::where('company_id', $this->selectedFromCompany)->where('plant_id', $this->selectedFromPlant)->where('sloc_id', $this->from_warehouse)->first();
            if ($stock) {
                $qtySoh = (float) $stock->qty_soh;
                $qtyIntransit = (float) $stock->qty_intransit;

                if ($qtySoh != 0.00) {
                    $this->dispatch('error', 'Quantity SOH is not empty: ' . $stock->qty_soh);
                } elseif ($qtyIntransit != 0.00) {
                    $this->dispatch('error', 'Quantity Intransit is not empty: ' . $stock->qty_intransit);
                } else {
                    $stock->update([
                        'company_id' => $this->selectedToCompany,
                        'plant_id' => $this->selectedToPlant,
                    ]);
                    $stock->save();
                    Sloc::where('id', $this->from_warehouse)->update([
                        'company_id' => $this->selectedToCompany,
                        'plant_id' => $this->selectedToPlant,
                    ]);
                    SlocTransfer::create([
                        'company_id' => $this->selectedToCompany,
                        'plant_id' => $this->selectedToPlant,
                        'sloc_id' => $this->from_warehouse,
                        'notes' => $this->notes,
                    ]);
                    DB::commit();
                    $this->dispatch('success', 'Transfer Warehouse Success');
                }
            } else {
                $this->dispatch('error', 'Material stock not found');
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
    public function closeModal()
    {
        $this->selectedFromCompany = null;
        $this->selectedFromPlant = null;
        $this->from_warehouse = null;
        $this->selectedToCompany  = null;
        $this->selectedToPlant = null;
        $this->notes = null;
    }
}
