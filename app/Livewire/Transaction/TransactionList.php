<?php

namespace App\Livewire\Transaction;

use App\Models\Company;
use App\Models\Material\Material;
use App\Models\Plant;
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

    public function posting($id,$warehouse,$date)
    {
        $message = false;
        $tmpDatas = TmpTransaction::where('trans_date',$date)->where('fuel_warehouse',$warehouse)->get();
        if ($tmpDatas->isNotEmpty()) {
            foreach($tmpDatas as $value) {
                $message = $this->cekData($value);
                $message = false;//hapus untuk kondisi asli
                if ($message) {
                    $value->update([
                        'status_error' => $message
                    ]);
                }
            }
            if ($message) {
                $this->dispatch('error', $message);
            }else{
                /* Save nya langsung per batch jika tidak ada error di validasi cekData() */
                $this->storeData($tmpDatas);

            }
        }else{
            $this->dispatch('error', 'There is no data toposting');
        }
    }
    public function storeData($data)
    {
        DB::beginTransaction();
        $lastPosting = Transaction::max('posting_no');
        $parts = explode('-', $lastPosting);
        $lastPostingNumber = end($parts);
        $newPostingNumber = str_pad($lastPostingNumber ? $lastPostingNumber + 1 : 1, 6, '0', STR_PAD_LEFT);
        try {
            foreach ($data as $tmp) {
                $company = Company::where('company_code',$tmp->company_code)->first();
                $fuelman = '';/* master fuelman belum */
                $equipment = ''; /* master equipment belum */
                $location = '';
                $activity = '';
                $fuelType = Material::find($tmp->fuel_type)->first();
                Transaction::create([
                    'company_id'    => $company->id,
                    'posting_no'    => 'POS-'.$newPostingNumber,
                    'trans_type'    => $tmp->trans_type,
                    'trans_date'    => $tmp->trans_date,
                    'fuelman_id'    => 1, /* ambil dari master $fuelman->id*/
                    'fuelman_name'  => 'fuelman', /* ambil dari master $fuelman->name*/
                    'equipment_id'  => 1,
                    'equipment_no'   => 'equipment',
                    'location_id'   => 1,
                    'location_name' => 'location',
                    'department'    => $tmp->department, // tidak ada master ?
                    'activity_id'   => 1,
                    'activity_name' => 'activity',
                    'fuel_type'     => $fuelType->material_description ?? 'SOLAR',
                    'qty'           => $tmp->qty,
                    'statistic_type'    => $tmp->statistic_type,
                    'meter_'        => $tmp->meter_value,
                ]);
                TmpTransaction::destroy($tmp->id);
            }
            DB::commit();
        $this->dispatch('success', 'Data has been posting');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', $e->getMessage());
            // throw $e; // Lempar kembali pengecualian untuk ditangani di tempat lain
        }
        
        
    }

    private function cekData($val)
    {
        $message = false;
        $companyExists = Company::where('company_code',$val->company_code)->exists();
        $fuelWarehouseExist = Plant::where('plant_code',$val->fuel_warehouse)->exists();
        $transTypeInvalid = in_array($val->trans_type, ['ISS', 'TRF', 'RCV']);
        $fuelmanExist = true;//?;
        $equipmentExist = true;
        $locationExist = true;
        $departmentExist = true;
        $activityExist = true;
        $fuelTypeExist = Material::find($val->fuel_type)->exists();

        if (!$companyExists) {
            $message = 'Company code '.$val->company_code.' not registered in master';
        }
        if (!$fuelWarehouseExist) {
            $message = 'Fuel warehouse '.$val->fuel_warehouse.' not registered in master';
        }
        if (!$transTypeInvalid) {
            $message = 'Trans type ' . $val->trans_type . ' unknown';
        }
        if (!$fuelmanExist) {
            $message = 'Fuelman '.$val->fuelman.' not registered in master';
        }
        if (!$equipmentExist) {
            $message = 'Equipment '.$val->equipment_no.' not registered in master';
        }
        if (!$locationExist) {
            $message = 'Location '.$val->location.' not registered in master';
        }
        if (!$departmentExist) {
            $message = 'Department '.$val->department.' not registered in master';
        }
        if (!$activityExist) {
            $message = 'Activity '.$val->activity.' not registered in master';
        }
        if (!$fuelTypeExist) {
            $message = 'Fuel type '.$val->fuel_type.' not registered in master';
        }
        return $message;
    }


}
