<?php

namespace App\Livewire\Transaction;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Fuelman;
use App\Models\Issue;
use App\Models\Material\Material;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Plant;
use App\Models\Sloc;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class IssueList extends Component
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
        $permissions = [
            'view-loader-issue'
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $issues = Issue::sumQty($this->filter_date);
        return view('livewire.transaction.issue-list', ['issues' => $issues]);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $transaction = Issue::findOrFail($id);
            $transaction->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function deleteSumary($warehouse, $date)
    {
        try {
            Issue::where('trans_date', $date)->whereNull('posting_no')->where('warehouse', $warehouse)->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function posting($id, $warehouse, $date)
    {
        $message = false;
        $tmpDatas = Issue::where('trans_date', $date)->where('warehouse', $warehouse)->get();
        if ($tmpDatas->isNotEmpty()) {
            foreach ($tmpDatas as $value) {
                $message = $this->cekData($value);
                // $message = false; //hapus untuk kondisi asli
                if ($message) {
                    $value->update([
                        'error_status' => $message
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
        $date = new DateTime($data[0]->trans_date);
        $year = $date->format('Y');

        $lastPosting = Issue::where('company_code', $data[0]->company_code)->max('posting_no');
        $number = 0;
        if (isset($lastPosting)) {
            $explod = explode('/', $lastPosting);
            if ($explod[0] == $year) {
                $number = $explod[2];
            }
        }
        $newPostingNumber = $year . '/' . $data[0]->company_code . '/' . str_pad($number + 1, 6, '0', STR_PAD_LEFT);
        try {
            foreach ($data as $tmp) {
                $company = Company::where('company_code', $tmp->company_code)->first();
                $location = Plant::where('plant_code', $tmp->location)->first();
                $fuelType = Material::where('material_code', $tmp->material_code)->first();
                $slocId = Sloc::where('sloc_code',  $tmp->warehouse)->value('id');

                Issue::find($tmp->id)->update(['posting_no' => $newPostingNumber]);

                $cekStok = MaterialStock::where('company_id', $company->id)
                    ->where('plant_id', $location->id)->where('sloc_id', $slocId)
                    ->first();
                $paramMovement = [
                    'company_id'    => $company->id,
                    'doc_header_id' => $tmp->id,
                    'doc_no'        => $newPostingNumber,
                    'doc_detail_id' => $tmp->id,
                    'material_id'   => $fuelType->id,
                    'material_code' => $fuelType->material_code,
                    'part_no'       => $fuelType->part_no,
                    'material_mnemonic' => $fuelType->material_mnemonic,
                    'material_description' => $fuelType->material_description,
                    'movement_date' => $tmp->trans_date,
                    'movement_type' => $tmp->trans_type,
                    'plant_id'  => $location->id,
                    'sloc_id'   =>  $slocId,
                    'uom_id'    => $fuelType->uom_id,
                    'soh_before' => $cekStok->qty_soh,
                    'intransit_before' => $cekStok->qty_intransit,
                    'qty'       => $tmp->qty,
                    'soh_after' => toNumber($cekStok->qty_soh) - toNumber($tmp->qty),
                    'intransit_after' => $cekStok->qty_intransit,
                ];
                MaterialMovement::create($paramMovement);
                if ($cekStok) {
                    $newStock = $cekStok->qty_soh - $tmp->qty;
                    /* delete validate issue minus
                        if ($newStock < 0) {
                            throw new \Exception('Insufficient stock to issue');
                        }
                     */
                    $cekStok->qty_soh = $newStock;
                    $cekStok->save();
                } else {
                    throw new \Exception('Material stock not found.');
                }
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
        $companyAllowed = Company::allowed('create-loader-issue')
            ->where('company_code', $val->company_code)
            ->first();
        $companyExists = Company::where('company_code', $val->company_code)->exists();
        $fuelWarehouseExist = Sloc::where('sloc_code', $val->warehouse)->exists();
        $transTypeInvalid = in_array($val->trans_type, ['ISS']); /* Hanya untuk ISS/issued */
        $fuelmanExist = Fuelman::where('nik', $val->fuelman)->exists();
        $equipmentExist = Equipment::where('equipment_no', $val->equipment_no)->exists();
        $locationExist = Plant::where('plant_code', $val->location)->exists();
        $departmentExist = Department::where('department_code', $val->department)->exists();
        $activityExist = Activity::where('activity_code', $val->activity)->exists();
        $fuelTypeExist = Material::where('material_code', $val->material_code)->exists();

        if (!$companyAllowed) {
            $message = 'Anda tidak punya akses Company code ' . $val->company_code;
        }
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
