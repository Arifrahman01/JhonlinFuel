<?php

namespace App\Livewire\Period;

use App\Models\Company;
use App\Models\Issue;
use App\Models\Material\MaterialStock;
use App\Models\Period;
use App\Models\Receipt;
use App\Models\ReceiptTransfer;
use App\Models\Sloc;
use App\Models\StockClosure;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;
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

    public $selectedYear;
    public $selectedMonth;

    public $periodId, $periodName, $startDate, $endDate;

    public $q;


    public function mount()
    {
        $this->selectedYear = date('Y');
        $this->selectedMonth = date('n');

        // dd($this->periodCompanies);

        // $period = Period::latest()->first();
        // $this->periodCompanies = data_get($period, 'companies');
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
            // $this->periodCompanies = data_get($period, 'companies');
        } else {
            $this->periodName = null;
            $this->startDate = null;
            $this->endDate = null;
            // $this->periodCompanies = collect();
        }

        $periodCompanies = Company::with(['periods' => function ($query) {
            $query->select('periods.id', 'periods.period_name', 'periods.year', 'periods.month', 'company_period.status')
                ->where('periods.year', $this->selectedYear)
                ->where('periods.month', $this->selectedMonth);
        }])->get(['companies.id', 'companies.company_code', 'companies.company_name']);

        // $periodCompanies = Company::whereHas('periods', function ($query) {
        //     $query->where('year', $this->selectedYear)
        //         ->where('month', $this->selectedMonth);
        // })->with(['periods' => function ($query) {
        //     $query->select('periods.id', 'periods.period_name', 'periods.year', 'periods.month', 'company_period.status')
        //         ->where('year', $this->selectedYear)
        //         ->where('month', $this->selectedMonth);
        // }])->get(['companies.id', 'companies.company_code', 'companies.company_name']);
        // dd(data_get($periodCompanies, '*.periods'));
        return view('livewire.period.period-list', [
            'years' => getListTahun(),
            'months' => getListBulan(),
            'periodCompanies' => $periodCompanies,
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
        DB::beginTransaction();
        try {
            $period = Period::find($periodId);

            if ($period) {
                $period->companies()->updateExistingPivot($companyId, ['status' => 'open']);
            } else {
                throw new \Exception('Periode tidak ditemukan');
            }

            $errorTransaksi = $this->cekTransaksi($companyId);
            if (!$errorTransaksi) {
                throw new \Exception($errorTransaksi);
            }

            $currentStocks = MaterialStock::where('company_id', $companyId)
                ->get();

            foreach ($currentStocks as $currentStock) {
                StockClosure::create([
                    'period_id' => $periodId,
                    'company_id' => $companyId,
                    'plant_id' => $currentStock->plant_id,
                    'sloc_id' => $currentStock->sloc_id,
                    'material_id' => $currentStock->material_id,
                    'material_code' => $currentStock->material_code,
                    'part_no' => $currentStock->part_no,
                    'material_mnemonic' => $currentStock->material_mnemonic,
                    'material_description' => $currentStock->material_description,
                    'uom_id' => $currentStock->uom_id,
                    'qty_soh' => $currentStock->qty_soh,
                    'qty_intransit' => $currentStock->qty_intransit,
                    'trans_type' => 'opening',
                ]);
            }
            DB::commit();
            $this->dispatch('success', 'Data has been updated');
            $this->resetPage();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function closePeriod($periodId, $companyId)
    {
        $permissions = [
            'close-period',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::beginTransaction();
        try {
            $period = Period::find($periodId);

            if ($period) {
                $period->companies()->updateExistingPivot($companyId, ['status' => 'close']);
            } else {
                throw new \Exception('Period not found');
            }

            $errorTransaksi = $this->cekTransaksi($companyId);
            if (!$errorTransaksi) {
                throw new \Exception($errorTransaksi);
            }

            $currentStocks = MaterialStock::where('company_id', $companyId)
                ->get();

            foreach ($currentStocks as $currentStock) {
                StockClosure::create([
                    'period_id' => $periodId,
                    'company_id' => $companyId,
                    'plant_id' => $currentStock->plant_id,
                    'sloc_id' => $currentStock->sloc_id,
                    'material_id' => $currentStock->material_id,
                    'material_code' => $currentStock->material_code,
                    'part_no' => $currentStock->part_no,
                    'material_mnemonic' => $currentStock->material_mnemonic,
                    'material_description' => $currentStock->material_description,
                    'uom_id' => $currentStock->uom_id,
                    'qty_soh' => $currentStock->qty_soh,
                    'qty_intransit' => $currentStock->qty_intransit,
                    'trans_type' => 'closing',
                ]);
            }
            DB::commit();
            $this->dispatch('success', 'Data has been updated');
            $this->resetPage();
        } catch (\Throwable $th) {
            DB::rollBack();
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
            return 'Ada Receipt PO yang belum posting';
        }

        if ($transferBelumPosting) {
            return 'Ada Transfer yang belum posting';
        }

        if ($receiptTransferBelumPosting) {
            return 'Ada Receipt Transfer yang belum posting';
        }

        if ($issueBelumPosting) {
            return 'Ada Issue yang belum posting';
        }

        if ($osTransfer) {
            return 'Ada Transfer yang belum diReceipt Transfer';
        }

        return false;
    }
}
