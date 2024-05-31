<?php

namespace App\Livewire\Transaction;

use App\Models\Company;
use App\Models\Equipment;
use App\Models\Material\Material;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Plant;
use App\Models\Receipt;
use App\Models\Sloc;
use App\Models\Uom;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class ReceiptList extends Component
{
    use WithPagination;
    public $filter_date;
    public $filter_search;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public function mount()
    {
        $this->filter_date = $this->filter_date ?? date('Y-m-d');
    }

    public function render()
    {
        $permissions = [
            'view-loader-receipt-po'
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $receipts = Receipt::whereNull('posting_no')
            ->where('trans_date', $this->filter_date)->search($this->filter_search)
            ->paginate(10);
        return view('livewire.transaction.receipt-list', ['receipts' => $receipts]);
    }

    public function delete($id)
    {
        $permissions = [
            'view-loader-receipt-po'
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        try {
            Receipt::whereIn('id', $id)->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
    public function posting($id)
    {
        $message = false;
        $data = Receipt::whereIn('id', $id)->get();
        if ($data->isNotEmpty()) {
            foreach ($data as $key => $value) {
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
                $this->storeData($data);
            }
        } else {
            $this->dispatch('error', 'There is no data to posting');
        }
    }

    public function storeData($data)
    {
        DB::beginTransaction();
        $lastPosting = Receipt::max('posting_no');
        if (isset($lastPosting)) {
            $explod = explode('/', $lastPosting);
            if ($explod[0] == date('Y')) {
                $number = $explod[2];
            } else {
                $number = 0;
            }
        } else {
            $number = 0;
        }

        $newPostingNumber = date('Y') . '/' . $data[0]->company_code . '/' . str_pad($number + 1, 6, '0', STR_PAD_LEFT);
        try {
            foreach ($data as $tmp) {
                $company = Company::where('company_code', $tmp->company_code)->first();
                // $fuelman = Fuelman::where('nik', $tmp->fuelman)->first();
                // $equipment = Equipment::where('equipment_no', $tmp->equipment_no)->first();
                $location = Plant::where('plant_code', $tmp->location)->first();
                // $activity = Activity::where('id', $tmp->activity)->first();
                $fuelType = Material::where('material_code', $tmp->material_code)->first();
                $slocId = Sloc::where('sloc_code',  $tmp->warehouse)->value('id');
                $uomId = Uom::where('uom_code', $tmp->uom)->value('id');
                Receipt::find($tmp->id)->update(['posting_no' => $newPostingNumber]);

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
                    // 'movement_date' => date('Y-m-d'),
                    'movement_type' => $tmp->trans_type,
                    'plant_id'  => $location->id,
                    'sloc_id'   =>  $slocId,
                    'uom_id'    =>  $uomId,
                    'qty'       => $tmp->qty,
                ];

                MaterialMovement::create($paramMovement);
                $cekStok = MaterialStock::where('company_id', $company->id)->where('plant_id', $location->id)->where('sloc_id', $slocId)->first();
                if ($cekStok) {
                    $newStock = $cekStok->qty_soh + $tmp->qty;
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


    public function search()
    {
        $this->resetPage();
    }
    private function cekData($data)
    {
        $message = false;
        $companyExists = Company::where('company_code', $data->company_code)->exists();
        $transTypeInvalid = in_array($data->trans_type, ['RCV']);
        $locationExist = Plant::where('plant_code', $data->location)->exists();
        $fuelWarehouseExist = Sloc::where('sloc_code', $data->warehouse)->exists();
        $transportirExist = Equipment::where('equipment_no', $data->transportir)->exists();
        $materialExist = Material::where('material_code', $data->material_code)->exists();


        if (!$companyExists) {
            $message = 'Company code ' . $data->company_code . ' not registered in master';
        }
        if (!$transTypeInvalid) {
            $message = 'Trans type ' . $data->trans_type . ' unknown';
        }
        if (!$locationExist) {
            $message = 'Location ' . $data->location . ' not registered in master';
        }
        if (!$fuelWarehouseExist) {
            $message = 'Fuel warehouse ' . $data->warehouse . ' not registered in master';
        }
        if (!$transportirExist) {
            $message = 'Transportir ' . $data->transportir . ' not registered in master';
        }
        if (!$materialExist) {
            $message = 'Material Code ' . $data->material_code . ' not registered in master';
        }
        return $message;
    }
}
