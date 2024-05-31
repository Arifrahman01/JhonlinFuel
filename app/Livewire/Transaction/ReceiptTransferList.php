<?php

namespace App\Livewire\Transaction;

use App\Models\Company;
use App\Models\Equipment;
use App\Models\Material\Material;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\ReceiptTransfer;
use App\Models\Sloc;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ReceiptTransferList extends Component
{

    use WithPagination;
    public $dateFilter;
    protected $listeners = ['refreshPage'];
    public function render()
    {
        $permissions = [
            'view-loader-receipt-transfer',
            'create-loader-receipt-transfer',
            'edit-loader-receipt-transfer',
            'delete-loader-receipt-transfer',
            'posting-loader-receipt-transfer',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rcvTransfers = ReceiptTransfer::with([
            'fromCompany',
            'toCompany',
            'fromWarehouse',
            'toWarehouse',
        ])
            ->whereNull('posting_no')
            ->when($this->dateFilter, function ($query, $dateFilter) {
                $query->where('trans_date', $dateFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.transaction.receipt-transfer-list', compact('rcvTransfers'));
    }

    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $permissions = [
            'delete-loader-receipt-transfer',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            ReceiptTransfer::whereIn('id', $id)->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function posting($id)
    {
        $permissions = [
            'posting-loader-receipt-transfer',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $message = false;
        $data = ReceiptTransfer::whereIn('id', $id)->get();
        if ($data->isNotEmpty()) {
            foreach ($data as $key => $value) {
                $message = $this->cekData($value);
            }
            if ($message) {
                $value->update([
                    'error_status' => $message
                ]);
                $this->dispatch('error', $message);
            } else {
                $this->storeData($data);
            }
        } else {
            $this->dispatch('error', 'There is no data to posting');
        }
    }

    private function cekData($data)
    {
        $message = false;
        $fromCompanyAllowed = Company::allowed('create-loader-receipt-transfer')
            ->where('company_code', $data->from_company_code)
            ->first();
        $fromCompanyExists = Company::where('company_code', $data->from_company_code)->exist();
        $fromWarehouseExists = Sloc::where('sloc_code', $data->from_warehouse)->exists();
        $toCompanyExists = Company::where('company_code', $data->to_company_code)->exists();
        $toWarehouseExists = Sloc::where('sloc_code', $data->to_warehouse)->exists();
        $transportirExist = Equipment::where('equipment_no', $data->transportir)->exists();
        $materialExist = Material::where('material_code', $data->material_code)->exists();
        $qtyTransitFromWarehouse = MaterialStock::where('sloc_id', Sloc::where('sloc_code', $data->from_warehouse)
            ->value('id'))
            ->value('qty_intransit');

        if (!$fromCompanyAllowed) {
            $message = 'Anda tidak punya akses Company code from ' . $data->from_company_code;
        }
        if (!$fromCompanyExists) {
            $message = 'Company code from ' . $data->from_company_code . ' not registered in master';
        }
        if (!$fromWarehouseExists) {
            $message = 'Warehouse from ' . $data->from_warehouse . ' not registered in master';
        }
        if (!$toCompanyExists) {
            $message = 'Company code to ' . $data->to_company_code . ' not registered in master';
        }
        if (!$toWarehouseExists) {
            $message = 'Warehouse to' . $data->to_warehouse . ' not registered in master';
        }
        if (!$transportirExist) {
            $message = 'Transportir ' . $data->transportir . ' not registered in master';
        }
        if (!$materialExist) {
            $message = 'Material Code ' . $data->material_code . ' not registered in master';
        }

        if ($qtyTransitFromWarehouse >= 0 || abs($qtyTransitFromWarehouse) < $data->qty) {
            $message = 'Qty Transit from warehouse is not enough';
        }

        return $message;
    }

    public function storeData($data)
    {
        DB::beginTransaction();
        try {
            $date = new DateTime($data[0]->trans_date);
            $year = $date->format('Y');
            $lastPosting = ReceiptTransfer::where('from_company_code', $data[0]->company_code)
                ->max('posting_no');
            $number = 0;
            if (isset($lastPosting)) {
                $explod = explode('/', $lastPosting);
                if ($explod[0] == $year) {
                    $number = $explod[2];
                }
            }
            $newPostingNumber = $year . '/' . $data[0]->from_company_code . '/' . str_pad($number + 1, 6, '0', STR_PAD_LEFT);

            foreach ($data as $receiptTransfer) {

                $receiptTransfer->update([
                    'posting_no' => $newPostingNumber,
                ]);

                $stokFrom = MaterialStock::where('sloc_id', Sloc::where('sloc_code', $receiptTransfer->from_warehouse)->value('id'));

                $stokFrom->decrement('qty_soh', $receiptTransfer->qty);
                $stokFrom->increment('qty_intransit', $receiptTransfer->qty);

                $slocTo = Sloc::where('sloc_code', $receiptTransfer->to_warehouse)->first();
                $stokTo = MaterialStock::where('sloc_id', $slocTo->id);

                $stokTo->increment('qty_soh', $receiptTransfer->qty);
                $stokTo->decrement('qty_intransit', $receiptTransfer->qty);

                $material = Material::where('material_code', $receiptTransfer->material_code)->first();

                MaterialMovement::create([
                    'company_id' => $slocTo->company_id,
                    'doc_header_id' => $receiptTransfer->id,
                    'doc_no' => $newPostingNumber,
                    'doc_detail_id' => $receiptTransfer->id,
                    'material_id' => $material->id,
                    'material_code' => $material->material_code,
                    'part_no' => $material->part_no,
                    'material_mnemonic' => $material->material_mnemonic,
                    'material_description' => $material->material_description,
                    'movement_date' => $receiptTransfer->trans_date,
                    'movement_type' => 'rct',
                    'plant_id' => $slocTo->plant_id,
                    'sloc_id' => $slocTo->id,
                    'uom_id' => 1,
                    'qty' => $receiptTransfer->qty,
                ]);
            }
            DB::commit();
            $this->dispatch('success', 'Data has been posting :' . $newPostingNumber);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
