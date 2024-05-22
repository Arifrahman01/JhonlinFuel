<?php

namespace App\Livewire\Transaction;

use App\Models\Company;
use App\Models\Equipment;
use App\Models\Material\Material;
use App\Models\Plant;
use App\Models\Receipt;
use App\Models\Sloc;
use Livewire\Component;
use Livewire\WithPagination;

class ReceiptList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public function render()
    {
        $receipts = Receipt::paginate(5);
        return view('livewire.transaction.receipt-list', ['receipts' => $receipts]);
    }

    public function delete($id)
    {
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
            }
        } else {
            $this->dispatch('error', 'There is no data to posting');
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
        $locationExist = Plant::where('id', $data->location)->exists();
        $fuelWarehouseExist = Sloc::where('sloc_code', $data->fuel_warehouse)->exists();
        $transportirExist = Equipment::where('equipment_no', $data->transportir)->exists();
        $materialExist = Material::where('id', $data->material_code)->exists();
       

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
            $message = 'Fuel warehouse ' . $data->fuel_warehouse . ' not registered in master';
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
