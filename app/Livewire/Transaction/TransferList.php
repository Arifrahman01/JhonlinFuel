<?php

namespace App\Livewire\Transaction;

use App\Models\Company;
use App\Models\Equipment;
use App\Models\Material\Material;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Sloc;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;
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
        $transfers = Transfer::where('trans_date',$this->filter_date)->whereNull('posting_no')->search($this->filter_search)->paginate(10);
        return view('livewire.transaction.transfer-list',['transfers' => $transfers]);
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
                        'status_error' => $message
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
        $fromCompanyExists = Company::where('company_code', $data->from_company_code)->exists();
        $fromWarehouseExists = Sloc::where('sloc_code', $data->from_warehouse)->exists();
        $toCompanyExists = Company::where('company_code', $data->to_company_code)->exists();
        $toWarehouseExists = Sloc::where('sloc_code', $data->to_warehouse)->exists();
        $transportirExist = Equipment::where('equipment_no', $data->transportir)->exists();
        $materialExist = Material::where('id', $data->material_code)->exists();

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
                $number = $explod[0];
            } else {
                $number = 0;
            }
        } else {
            $number = 0;
        }
        $newPostingNumber = date('Y').'/'.$data[0]->from_company_code.'/'.str_pad($number + 1, 6, '0', STR_PAD_LEFT) ;
        try {
            foreach ($data as $tmp) {
                $fromCompany = Company::where('company_code', $tmp->from_company_code)->first();
                $toCompany  = Company::where('company_code', $tmp->to_company_code)->first();
                $fuelType = Material::where('id',$tmp->material_code)->first();
                $slocIdFrom = Sloc::where('sloc_code',  $tmp->from_warehouse)->first();
                $slocIdTo = Sloc::where('sloc_code', $tmp->to_warehouse)->value('id');

                Transfer::find($tmp->id)->update(['posting_no' => $newPostingNumber]);

                $paramMovement = [
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
                    'uom_id'    =>  1,
                    'qty'       => $tmp->qty,
                ];
                MaterialMovement::create($paramMovement);
                $cekStokFrom = MaterialStock::where('company_id', $fromCompany->id)->where('sloc_id', $slocIdFrom->id)->first();
                if ($cekStokFrom) {
                    $newStock = $cekStokFrom->qty_intransit - $tmp->qty;
                    $cekStokFrom->qty_intransit = $newStock;
                    $cekStokFrom->save();
                } else {
                    throw new \Exception('Material stock in the original warehouse was not found.');
                }
                $cekStokTo = MaterialStock::where('company_id', $toCompany->id)->where('sloc_id', $slocIdTo)->first();
                if ($cekStokTo) {
                    $newStockTo = $cekStokTo->qty_intransit + $tmp->qty;
                    $cekStokTo->qty_intransit = $newStockTo;
                    $cekStokTo->save();
                }else{
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
