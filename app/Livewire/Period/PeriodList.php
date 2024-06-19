<?php

namespace App\Livewire\Period;

use App\Models\Issue;
use App\Models\Material\MaterialStock;
use App\Models\Period;
use App\Models\Receipt;
use App\Models\ReceiptTransfer;
use App\Models\Sloc;
use App\Models\StockClosure;
use App\Models\Transfer;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Str;

class PeriodList extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public $periodCompanies = [];

    public $periodId, $periodName, $startDate, $endDate;

    public $q;


    public function mount()
    {
        $period = Period::latest()->first();
        $this->periodCompanies = data_get($period, 'companies');
    }

    public function render()
    {
        $permissions = [
            'view-master-period',
            'create-master-period',
            'edit-master-period',
            'delete-master-period',
            'open-period',
            'close-period',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $periods = Period::with(['companies'])
            ->latest()
            ->paginate(10);

        $periodQuery = Period::with(['companies']);
        if ($this->periodId) {
            $periodQuery->where('id', $this->periodId);
        } else {
            $periodQuery->latest();
        }
        $period = $periodQuery->first();
        if ($period) {
            $this->periodName = $period->period_name;
            $this->startDate = $period->start_date;
            $this->endDate = $period->end_date;
            $this->periodCompanies = data_get($period, 'companies');
        } else {
            $this->periodName = null;
            $this->startDate = null;
            $this->endDate = null;
            $this->periodCompanies = collect();
        }
        return view('livewire.period.period-list', [
            'periods' => $periods,
        ]);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $permissions = [
            'delete-master-period',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $period = Period::find($id);
            if ($period->hasDataById()) {
                throw new \Exception("period has data. Can't be deleted");
            }
            $period->delete();
            $this->periodId = null;
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function periodSelected($periodId)
    {
        $this->periodId = $periodId;
    }

    public function openPeriod($periodId, $companyId)
    {
        $permissions = [
            'open-period',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $period = Period::find($periodId);

            if ($period) {
                $period->companies()->updateExistingPivot($companyId, ['status' => 'open']);
            } else {
                throw new \Exception('Periode tidak ditemukan');
            }

            $this->cekTransaksi($companyId);

            $slocs = Sloc::where('company_id', $companyId)
                ->get();

            foreach ($slocs as $sloc) {
                StockClosure::create([
                    'period_id' => $periodId,
                    'company_id' => $companyId,
                    'plant_id' => $sloc->plant_id,
                    'sloc_id' => $sloc->sloc_id,
                    'material_id' => $sloc->material_id,
                    'material_code' => $sloc->material_code,
                    'part_no' => $sloc->part_no,
                    'material_mnemonic' => $sloc->material_mnemonic,
                    'material_description' => $sloc->material_description,
                    'uom_id' => $sloc->uom_id,
                    'qty_soh' => $sloc->qty_soh,
                    'qty_intransit' => $sloc->qty_intransit,
                    'trans_type' => 'opening',
                ]);
            }

            $this->dispatch('success', 'Data has been updated');
            $this->resetPage();
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function closePeriod($periodId, $companyId)
    {
        $permissions = [
            'close-period',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $period = Period::find($periodId);

            if ($period) {
                $period->companies()->updateExistingPivot($companyId, ['status' => 'close']);
            } else {
                throw new \Exception('Period not found');
            }

            $this->cekTransaksi($companyId);

            $slocs = Sloc::where('company_id', $companyId)
                ->get();

            foreach ($slocs as $sloc) {
                StockClosure::create([
                    'period_id' => $periodId,
                    'company_id' => $companyId,
                    'plant_id' => $sloc->plant_id,
                    'sloc_id' => $sloc->sloc_id,
                    'material_id' => $sloc->material_id,
                    'material_code' => $sloc->material_code,
                    'part_no' => $sloc->part_no,
                    'material_mnemonic' => $sloc->material_mnemonic,
                    'material_description' => $sloc->material_description,
                    'uom_id' => $sloc->uom_id,
                    'qty_soh' => $sloc->qty_soh,
                    'qty_intransit' => $sloc->qty_intransit,
                    'trans_type' => 'closing',
                ]);
            }

            $this->dispatch('success', 'Data has been updated');
            $this->resetPage();
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    private function cekTransaksi($companyId)
    {

        $receiptBelumPosting = Receipt::whereNull('posting_no')
            ->exists();
        $transferBelumPosting = Transfer::whereNull('posting_no')
            ->exists();
        $receiptTransferBelumPosting = ReceiptTransfer::whereNull('posting_no')
            ->exists();
        $issueBelumPosting = Issue::whereNull('posting_no')
            ->exists();
        $osTransfer = MaterialStock::where('company_id', $companyId)
            ->whereNotNull('qty_intransit')
            ->where('qty_intransit', '!=', 0)
            ->exists();

        if ($receiptBelumPosting) {
            throw new \Exception('Ada Receipt PO yang belum posting');
        }

        if ($transferBelumPosting) {
            throw new \Exception('Ada Transfer yang belum posting');
        }

        if ($receiptTransferBelumPosting) {
            throw new \Exception('Ada Receipt Transfer yang belum posting');
        }

        if ($issueBelumPosting) {
            throw new \Exception('Ada Issue yang belum posting');
        }

        if ($osTransfer) {
            throw new \Exception('Ada Transfer yang belum diReceipt Transfer');
        }
    }
}
