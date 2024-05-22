<?php

namespace App\Livewire\Transaction;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Equipment;
use App\Models\Fuelman;
use App\Models\Material\Material;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Plant;
use App\Models\Sloc;
use App\Models\Transaction\TmpTransaction;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class TransactionList extends Component
{
    use WithPagination;
    public $filter_date;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];


    public function mount()
    {
        $this->filter_date = $this->filter_date ?? date('Y-m-d');
    }

    public function render()
    {
        $transactions = TmpTransaction::sumQty2($this->filter_date);
        return view('livewire.transaction.transaction-list', ['transactions' => $transactions]);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $transaction = TmpTransaction::findOrFail($id);
            $transaction->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function posting($id, $warehouse, $date)
    {
        $message = false;
        $tmpDatas = TmpTransaction::where('trans_date', $date)->where('fuel_warehouse', $warehouse)->get();
        if ($tmpDatas->isNotEmpty()) {
            foreach ($tmpDatas as $value) {
                $message = $this->cekData($value);
                // $message = false; //hapus untuk kondisi asli
                if ($message) {
                    $value->update([
                        'status_error' => $message
                    ]);
                }
            }
            if ($message) {
                $this->dispatch('error', $message);
            } else {
                /* Save nya langsung per batch jika tidak ada error di validasi cekData() */
                $this->storeData($tmpDatas);
            }
        } else {
            $this->dispatch('error', 'There is no data to posting');
        }
    }
    public function storeData($data)
    {
        DB::beginTransaction();
        $lastPosting = Transaction::max('posting_no');
        if (isset($lastPosting)) {
            $explod = explode('/', $lastPosting);
            if ($explod[1] == date('Y')) {
                $number = $explod[0];
            } else {
                $number = 0;
            }
        } else {
            $number = 0;
        }

        $newPostingNumber = str_pad($number + 1, 6, '0', STR_PAD_LEFT) . '/' . $data[0]->company_code . '/' . date('Y');
        try {
            foreach ($data as $tmp) {
                $company = Company::where('company_code', $tmp->company_code)->first();
                $fuelman = Fuelman::where('nik', $tmp->fuelman)->first();
                $equipment = Equipment::where('equipment_no', $tmp->equipment_no)->first();
                $location = Plant::where('id', $tmp->location)->first();
                $activity = Activity::where('id', $tmp->activity)->first();
                $fuelType = Material::find($tmp->fuel_type)->first();
                $slocId = Sloc::where('sloc_code',  $tmp->fuel_warehouse)->value('id');

                $saveTransaction = Transaction::create([
                    'company_id'    => $company->id,
                    'posting_no'    => $newPostingNumber,
                    'trans_type'    => $tmp->trans_type,
                    'trans_date'    => $tmp->trans_date,
                    'fuelman_id'    => $fuelman->id,
                    'fuelman_name'  => $fuelman->name,
                    'equipment_id'  => $equipment->id,
                    'equipment_no'  => $equipment->equipment_no,
                    'location_id'   => $location->id,
                    'location_name' => $location->plant_name,
                    'department'    => $tmp->department,
                    'activity_id'   => $activity->id,
                    'activity_name' => $activity->activity_name,
                    'fuel_type'     => $fuelType->material_description,
                    'qty'           => $tmp->qty,
                    'statistic_type' => $tmp->statistic_type,
                    'meter_value'   => $tmp->meter_value,
                ]);
                $paramMovement = [
                    'company_id'    => $company->id,
                    'doc_header_id' => $saveTransaction->id,
                    'doc_no'        => $newPostingNumber,
                    'doc_detail_id' => $saveTransaction->id,
                    'material_id'   => $fuelType->id,
                    'material_code' => $fuelType->material_code,
                    'part_no'       => $fuelType->part_no,
                    'material_mnemonic' => $fuelType->material_mnemonic,
                    'material_description' => $fuelType->material_description,
                    'movement_date' => date('Y-m-d'),
                    'movement_type' => $tmp->trans_type,
                    'plant_id'  => $tmp->location,
                    'sloc_id'   =>  $slocId,
                    'uom_id'    => $fuelType->uom_id,
                    'qty'       => $tmp->qty,
                ];
                MaterialMovement::create($paramMovement);
                $cekStok = MaterialStock::where('company_id', $company->id)->where('plant_id', $tmp->location)->where('sloc_id', $slocId)->first();
                if ($cekStok) {
                    $newStock = $cekStok->qty_soh - $tmp->qty;
                    if ($newStock < 0) {
                        throw new \Exception('Insufficient stock to issue');
                    }
                    $cekStok->qty_soh = $newStock;
                    $cekStok->save();
                } else {
                    throw new \Exception('Material stock not found.');
                }
                TmpTransaction::destroy($tmp->id);
            }
            DB::commit();
            $this->dispatch('success', 'Data has been posting :' . $newPostingNumber);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', $e->getMessage());
        }
    }

    private function cekData($val)
    {
        $message = false;
        $companyExists = Company::where('company_code', $val->company_code)->exists();
        $fuelWarehouseExist = Sloc::where('sloc_code', $val->fuel_warehouse)->exists();
        $transTypeInvalid = in_array($val->trans_type, ['ISS']); /* Hanya untuk ISS/issued */
        $fuelmanExist = Fuelman::where('nik', $val->fuelman)->exists();
        $equipmentExist = Equipment::where('equipment_no', $val->equipment_no)->exists();
        $locationExist = Plant::where('id', $val->location)->exists();
        $departmentExist = true;
        $activityExist = Activity::where('id', $val->activity)->exists();
        $fuelTypeExist = Material::where('id',$val->fuel_type)->exists();

        if (!$companyExists) {
            $message = 'Company code ' . $val->company_code . ' not registered in master';
        }
        if (!$fuelWarehouseExist) {
            $message = 'Fuel warehouse ' . $val->fuel_warehouse . ' not registered in master';
        }
        if (!$transTypeInvalid) {
            $message = 'Trans type ' . $val->trans_type . ' unknown';
        }
        if (!$fuelmanExist) {
            $message = 'Fuelman ' . $val->fuelman . ' not registered in master';
        }
        if (!$equipmentExist) {
            $message = 'Equipment ' . $val->equipment_no . ' not registered in master';
        }
        if (!$locationExist) {
            $message = 'Location ' . $val->location . ' not registered in master';
        }
        if (!$departmentExist) {
            $message = 'Department ' . $val->department . ' not registered in master';
        }
        if (!$activityExist) {
            $message = 'Activity ' . $val->activity . ' not registered in master';
        }
        if (!$fuelTypeExist) {
            $message = 'Fuel type ' . $val->fuel_type . ' not registered in master';
        }
        return $message;
    }
}
