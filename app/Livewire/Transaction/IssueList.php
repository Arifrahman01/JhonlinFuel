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

    public function deleteSumary($ids)
    {
        try {
            $idArray = explode(',', $ids);
            Issue::whereIn($idArray)->whereNull('posting_no')->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function posting($id, $ids)
    {
        $message = [];
        $idArray = explode(',', $ids);
        $tmpDatas = Issue::whereIn('id',$idArray)->whereNull('posting_no')->get();
        if ($tmpDatas->isNotEmpty()) {
            foreach ($tmpDatas as $value) {
                $errorMessage  = $this->cekData($value);
                if ($errorMessage) {
                    $message[] = $errorMessage;
                    $value->update([
                        'error_status' => $errorMessage
                    ]);
                }
            }
            if (count($message) >= 1) {
                $this->dispatch('error', $message[0]);
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
                $issue = Issue::find($tmp->id);
                if (!$issue) {
                    throw new \Exception('Issue not found with ID: ' . $tmp->id);
                }

                $issue->update(['posting_no' => $newPostingNumber]);

                if (!checkOpenPeriod($company->id, $tmp->trans_date)) {
                    throw new \Exception('Periode tidak open untuk ' . $company->company_name . ' tanggal ' . $tmp->trans_date);
                }

                $cekStok = MaterialStock::where('company_id', $company->id)
                    ->where('plant_id', $location->id)->where('sloc_id', $slocId)
                    ->first();
                if (!$cekStok) {
                    throw new \Exception('Data material stock tidak terdaftar di <br> company : ' . $tmp->company_code . '<br>Location : ' .$tmp->location. '<br> Warehouse : '. $tmp->warehouse);
                }
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
        $company = Company::allowed('create-loader-issue')
            ->where('company_code', $val->company_code)
            ->first();
        $companyExists = Company::where('company_code', $val->company_code)->first();
        $locationExist = Plant::where('plant_code', $val->location)->where('company_id',$company->id)->exists();
        $fuelWarehouseExist = Sloc::where('sloc_code', $val->warehouse)->where('company_id',$companyExists->id)->first();
        $transTypeInvalid = in_array($val->trans_type, ['ISS']); /* Hanya untuk ISS/issued */
        $fuelmanExist = Fuelman::where('nik', $val->fuelman)->exists();
        $equipmentExist = Equipment::where('equipment_no', $val->equipment_no)->exists();
     
        $departmentExist = Department::where('department_code', $val->department)->exists();
        $activityExist = Activity::where('activity_code', $val->activity)->exists();
        $fuelTypeExist = Material::where('material_code', $val->material_code)->exists();

        if (!$company) {
            $message = 'Anda tidak punya akses Company code ' . $val->company_code;
        }
        if (!$companyExists) {
            $message = 'Company code ' . $val->company_code . 'tidak terdaftar di master';
        }
        if (!$locationExist) {
            $message = 'Location tidak terdaftar di master <br> Location : ' . $val->location . '<br> Company : '.$val->company_code;
        }
        if (!$fuelWarehouseExist) {
            $message = 'Warehouse tidak terdaftar di master <br> Company : ' . $val->company_code . ' <br> Location : '. $val->location .' <br> Warehouse : '.$val->warehouse;
        }
        if (!$transTypeInvalid) {
            $message = 'Trans type ' . $val->trans_type . ' tidak dikenal';
        }
        if (!$fuelmanExist) {
            $message = 'Fuelman ' . $val->fuelman . ' tidak terdaftar di master';
        }
        if (!$equipmentExist) {
            $message = 'Equipment ' . $val->equipment_no . ' tidak terdaftar di master';
        }
        
        if (!$departmentExist) {
            $message = 'Department ' . $val->department . 'tidak terdaftar di master';
        }
        if (!$activityExist) {
            $message = 'Activity ' . $val->activity . 'tidak terdaftar di master';
        }
        if (!$fuelTypeExist) {
            $message = 'Fuel type ' . $val->fuel_type . 'tidak terdaftar di master';
        }
        return $message;
    }
}
