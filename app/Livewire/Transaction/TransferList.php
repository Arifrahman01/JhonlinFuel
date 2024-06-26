<?php

namespace App\Livewire\Transaction;

use App\Models\Company;
use App\Models\Equipment;
use App\Models\Material\Material;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Sloc;
use App\Models\Transfer;
use App\Models\Uom;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class TransferList extends Component
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
            'view-loader-transfer'
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $transfers = Transfer::where('trans_date', $this->filter_date)->whereNull('posting_no')->search($this->filter_search)->paginate(10);
        return view('livewire.transaction.transfer-list', ['transfers' => $transfers]);
    }
    public function search()
    {
        $this->resetPage();
    }
    public function posting($id)
    {
        $message = false;
        $data = Transfer::whereIn('id', $id)->get();
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

    private function cekData($data)
    {
        $message = false;
        $fromCompanyAllowed = Company::allowed('create-loader-transfer')
            ->where('company_code', $data->from_company_code)
            ->first();
        $fromCompanyExists = Company::where('company_code', $data->from_company_code)->exists();
        $fromWarehouseExists = Sloc::where('sloc_code', $data->from_warehouse)->exists();
        $toCompanyExists = Company::where('company_code', $data->to_company_code)->exists();
        $toWarehouseExists = Sloc::where('sloc_code', $data->to_warehouse)->exists();
        $transportirExist = Equipment::where('equipment_no', $data->transportir)->exists();
        $materialExist = Material::where('material_code', $data->material_code)->exists();

        if (!$fromCompanyAllowed) {
            $message = 'Anda tidak punya akses Company code from ' . $data->from_company_code;
        }
        if (!$fromCompanyExists) {
            $message = 'From Company code ' . $data->from_company_code . ' not registered in master';
        }
        if (!$fromWarehouseExists) {
            $message = 'From Warehouse code ' . $data->from_warehouse . ' not registered in master';
        }
        if (!$toCompanyExists) {
            $message = 'To Company code ' . $data->to_company_code . ' not registered in master';
        }
        if (!$toWarehouseExists) {
            $message = 'To Warehouse code ' . $data->to_warehouse . ' not registered in master';
        }
        if (!$transportirExist) {
            $message = 'Transportir ' . $data->transportir . ' not registered in master';
        }
        if (!$materialExist) {
            $message = 'Material Code ' . $data->material_code . ' not registered in master';
        }
        return $message;
    }
    public function storeData($data)
    {
        DB::beginTransaction();
        $lastPosting = Transfer::max('posting_no');
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
        $newPostingNumber = date('Y') . '/' . $data[0]->from_company_code . '/' . str_pad($number + 1, 6, '0', STR_PAD_LEFT);
        $uom = Uom::first();
        try {
            foreach ($data as $tmp) {
                $fromCompany = Company::where('company_code', $tmp->from_company_code)->first();
                $toCompany  = Company::where('company_code', $tmp->to_company_code)->first();
                $fuelType = Material::where('material_code', $tmp->material_code)->first();
                $slocIdFrom = Sloc::where('sloc_code',  $tmp->from_warehouse)->first();
                $slocIdTo = Sloc::where('sloc_code', $tmp->to_warehouse)->first();

                Transfer::find($tmp->id)->update(['posting_no' => $newPostingNumber]);

                if (!checkOpenPeriod($fromCompany->id, $tmp->trans_date)) {
                    throw new \Exception('Periode tidak open untuk ' . $fromCompany->company_name . ' tanggal ' . $tmp->trans_date);
                }

                if (!checkOpenPeriod($toCompany->id, $tmp->trans_date)) {
                    throw new \Exception('Periode tidak open untuk ' . $toCompany->company_name . ' tanggal ' . $tmp->trans_date);
                }

                $cekStokFrom = MaterialStock::where('company_id', $fromCompany->id)->where('sloc_id', $slocIdFrom->id)->first();

                // movement gudang asal
                $paramMovementFrom = [
                    'company_id'    => $fromCompany->id,
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
                    'plant_id'  => $slocIdFrom->plant_id,
                    'sloc_id'   =>  $slocIdFrom->id,
                    'uom_id'    =>  $uom->id,
                    'soh_before' => $cekStokFrom->qty_soh,
                    'intransit_before' => $cekStokFrom->qty_intransit,
                    'qty'       => $tmp->qty,
                    'soh_after' => $cekStokFrom->qty_soh,
                    'intransit_after' => $cekStokFrom->qty_intransit - $tmp->qty,
                ];
                MaterialMovement::create($paramMovementFrom);

                $cekStokTo = MaterialStock::where('company_id', $toCompany->id)->where('sloc_id', $slocIdTo->id)->first();
                // movement gudang tujuan
                $paramMovementTo = [
                    'company_id'    => $toCompany->id,
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
                    'plant_id'  => $slocIdTo->plant_id,
                    'sloc_id'   =>  $slocIdTo->id,
                    'uom_id'    =>  $uom->id,
                    'soh_before' => $cekStokTo->qty_soh,
                    'intransit_before' => $cekStokTo->qty_intransit,
                    'qty'       => $tmp->qty,
                    'soh_after' => $cekStokTo->qty_soh,
                    'intransit_after' => $cekStokTo->qty_intransit + $tmp->qty,
                ];
                MaterialMovement::create($paramMovementTo);

                if ($cekStokFrom) {
                    $newStock = $cekStokFrom->qty_intransit - $tmp->qty;
                    $cekStokFrom->qty_intransit = $newStock;
                    $cekStokFrom->save();
                } else {
                    throw new \Exception('Material stock in the original warehouse was not found.');
                }
                if ($cekStokTo) {
                    $newStockTo = $cekStokTo->qty_intransit + $tmp->qty;
                    $cekStokTo->qty_intransit = $newStockTo;
                    $cekStokTo->save();
                } else {
                    throw new \Exception('Material stock in the destination warehouse was not found.');
                }
            }
            DB::commit();
            $this->dispatch('success', 'Data has been posting :' . $newPostingNumber);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            Transfer::whereIn('id', $id)->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
