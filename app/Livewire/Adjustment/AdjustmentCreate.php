<?php

namespace App\Livewire\Adjustment;

use App\Models\Adjustment\AdjustmentDetail;
use App\Models\Adjustment\AdjustmentHeader;
use App\Models\Company;
use App\Models\Material\Material;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Plant;
use App\Models\Sloc;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdjustmentCreate extends Component
{
    public $plants = [];
    public $selectedPlant;
    public $slocs = [];
    public $selectedSloc;
    public $soh = 0;
    public $sohAdjustmentReadOnly = true;
    public $sohAdjustment;
    public $sohAfter = 0;

    public $notes = '';

    public $datas = [];

    public $isLoadingSloc = false;
    public $isLoadingSoh = false;
    public $loading = false;

    protected $listeners = ['openModal'];

    public function render()
    {
        $userCompany = 1;
        $this->plants = Plant::where('company_id', $userCompany)->get();
        return view('livewire.adjustment.adjustment-create');
    }

    public function openModal()
    {
    }

    public function updatedSelectedPlant($value)
    {
        $this->dispatch('logData', 'Selected Plant: ' . $value);
        $this->soh = 0;
        $this->sohAdjustment = 0;
        $this->sohAfter = 0;
        $this->isLoadingSloc = true;
        try {
            $this->slocs = Sloc::where('plant_id', $value)->get();
            $this->sohAdjustmentReadOnly = true;
            $this->updateSohAfter();
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        } finally {
            $this->isLoadingSloc = false;
        }
    }

    public function updatedSelectedSloc($value)
    {
        $this->dispatch('logData', 'Selected Sloc: ' . $value);
        $this->isLoadingSoh = true;
        try {
            if (empty($value)) {
                $this->soh = 0;
                $this->sohAdjustmentReadOnly = true;
            } else {
                $materialStock = MaterialStock::where('sloc_id', $value)
                    ->first();
                $this->soh = $materialStock->qty_soh;
                $this->sohAdjustmentReadOnly = false;
            }
            $this->updateSohAfter();
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        } finally {
            $this->isLoadingSoh = false;
        }
    }

    public function updatedSohAdjustment($value)
    {
        $this->dispatch('logData', 'Soh Adjustment: ' . $value);
        $this->sohAfter = toNumber($this->soh) + (empty($value) ? 0 : $value);
    }

    private function updateSohAfter()
    {
        $this->sohAfter = toNumber($this->soh) + toNumber($this->sohAdjustment);
    }

    public function addData()
    {
        try {

            if (empty($this->selectedPlant)) {
                throw new \Exception('Plant tidak boleh kosong');
            }
            if (empty($this->selectedSloc)) {
                throw new \Exception('Sloc tidak boleh kosong');
            }
            if (empty($this->sohAdjustment) || $this->sohAdjustment == 0) {
                throw new \Exception('Adjust Qty tidak boleh nol');
            }

            if ($this->sohAdjustment < 0 && abs($this->sohAdjustment) > $this->soh) {
                throw new \Exception('Adjust Qty tidak boleh lebih dari Original Qty');
            }

            foreach ($this->datas as $data) {
                if ($data->sloc_id == $this->selectedSloc) {
                    throw new \Exception('Sloc sudah ada di daftar item');
                }
            }

            $plant = Plant::find($this->selectedPlant);
            $sloc = Sloc::find($this->selectedSloc);
            $data_ = (object) [
                'plant_id' => $this->selectedPlant,
                'plant' => $plant->plant_name,
                'sloc_id' => $this->selectedSloc,
                'sloc' => $sloc->sloc_name,
                'soh_before' => toNumber($this->soh),
                'soh_adjust' => toNumber($this->sohAdjustment),
                'soh_after' => toNumber($this->sohAfter),
                'notes' => $this->notes,
            ];
            array_push($this->datas, $data_);
            $this->resetForm();
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    private function resetForm()
    {
        $this->slocs = [];
        $this->selectedPlant = null;
        $this->selectedSloc = null;
        $this->soh = 0;
        $this->sohAdjustment = 0;
        $this->sohAfter = 0;
        $this->notes = '';
        $this->sohAdjustmentReadOnly = true;
    }

    public function deleteItem($index)
    {
        $this->dispatch('logData', 'Delete Item ' . $index);
        unset($this->datas[$index]);
        $this->datas = array_values($this->datas);
    }

    public function storeAdjustment()
    {
        $this->dispatch('logData', $this->datas);
        DB::beginTransaction();
        try {
            if (count($this->datas) == 0) {
                throw new \Exception('Item tidak boleh kosong');
            }
            $currentDate = date('Y-m-d');
            $currentYear = date('Y');
            $plant = Plant::find($this->datas[0]->plant_id);
            $company = Company::find($plant->company_id);
            $runningNumber = AdjustmentHeader::select(DB::raw("IFNULL(MAX(CAST(SUBSTR(adjustment_no, 1, 4) AS UNSIGNED)), 0) + 1 AS running_number"))
                ->where('company_id', $company->id)
                ->where(DB::raw("RIGHT(adjustment_no, 4)"), $currentYear)
                ->value('running_number');
            $adjustmentNo = str_pad($runningNumber, 4, '0', STR_PAD_LEFT) . '/ADJ/' . $company->company_code . '/' . $currentYear;
            $header = AdjustmentHeader::create([
                'company_id' => $plant->company_id,
                'adjustment_no' => $adjustmentNo,
            ]);

            $material = Material::find(1);

            foreach ($this->datas as $data) {
                $detail = AdjustmentDetail::create([
                    'header_id' => $header->id,
                    'company_id' => $plant->company_id,
                    'plant_id' => $data->plant_id,
                    'sloc_id' => $data->sloc_id,
                    'material_id' => 1,
                    'material_code' => $material->material_code,
                    'part_no' => $material->part_no,
                    'material_mnemonic' => $material->material_mnemonic,
                    'material_description' => $material->material_description,
                    'uom_id' => 1,
                    'origin_qty' => $data->soh_before,
                    'adjust_qty' => $data->soh_adjust,
                    'notes' => $data->notes,
                ]);

                MaterialMovement::create([
                    'company_id' => $plant->company_id,
                    'doc_header_id' => $header->id,
                    'doc_no' => $adjustmentNo,
                    'doc_detail_id' => $detail->id,
                    'material_id' => 1,
                    'material_code' => $material->material_code,
                    'part_no' => $material->part_no,
                    'material_mnemonic' => $material->material_mnemonic,
                    'material_description' => $material->material_description,
                    'movement_date' => $currentDate,
                    'movement_type' => 'adj',
                    'plant_id' => $data->plant_id,
                    'sloc_id' => $data->sloc_id,
                    'uom_id' => 1,
                    'qty' => $data->soh_adjust,
                ]);

                $qtyUpdate = toNumber($data->soh_before) + toNumber($data->soh_adjust);

                MaterialStock::where('company_id', $plant->company_id)
                    ->where('plant_id', $data->plant_id)
                    ->where('sloc_id', $data->sloc_id)
                    ->update([
                        'qty_soh' => $qtyUpdate,
                    ]);
            }
            DB::commit();
            $this->dispatch('success', 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function closeModal()
    {
        $this->plants = [];
        $this->selectedPlant = null;
        $this->slocs = [];
        $this->selectedSloc = null;
        $this->soh = 0;
        $this->sohAdjustmentReadOnly = true;
        $this->sohAdjustment = 0;
        $this->sohAfter = 0;
        $this->notes = '';
        $this->datas = [];
        $this->isLoadingSloc = false;
        $this->loading = false;
    }
}
