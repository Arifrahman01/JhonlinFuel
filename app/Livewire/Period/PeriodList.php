<?php

namespace App\Livewire\Period;

use App\Models\Adjustment\AdjustmentDetail;
use App\Models\Company;
use App\Models\Issue;
use App\Models\Material\Material;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Period;
use App\Models\Receipt;
use App\Models\ReceiptTransfer;
use App\Models\Sloc;
use App\Models\StockClosure;
use App\Models\Transfer;
use App\Models\Uom;
use Carbon\Carbon;
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

    public $periodCompanies;

    public $periodId, $periodName, $startDate, $endDate;

    public $q;


    public function mount()
    {
        // $this->selectedYear = date('Y');
        $this->selectedYear = 2024;
        // $this->selectedMonth = date('n');
        $this->selectedMonth = 1;

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

        $this->periodCompanies = Company::with(['periods' => function ($query) {
            $query->select('periods.id', 'periods.period_name', 'periods.year', 'periods.month', 'company_period.status')
                ->where('periods.year', $this->selectedYear)
                ->where('periods.month', $this->selectedMonth);
        }])->get(['companies.id', 'companies.company_code', 'companies.company_name']);

        dd(data_get($this->periodCompanies, '0.periods.0'));

        dd($this->periodCompanies[0]->periods->toArray());
        // $periodCompanies = Company::whereHas('periods', function ($query) {
        //     $query->where('year', $this->selectedYear)
        //         ->where('month', $this->selectedMonth);
        // })->with(['periods' => function ($query) {
        //     $query->select('periods.id', 'periods.period_name', 'periods.year', 'periods.month', 'company_period.status')
        //         ->where('year', $this->selectedYear)
        //         ->where('month', $this->selectedMonth);
        // }])->get(['companies.id', 'companies.company_code', 'companies.company_name']);
        // dd(data_get($periodCompanies, '0.periods.0.id'));
        return view('livewire.period.period-list', [
            'years' => getListTahun(),
            'months' => getListBulan(),
            // 'periodCompanies' => $periodCompanies,
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

    public function openPeriod($companyIds)
    {
        $permissions = [
            'open-period',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::beginTransaction();
        try {
            $period = Period::where('year', $this->selectedYear)
                ->where('month', $this->selectedMonth)
                ->first();

            if ($period) {
                $currentCompanies = $period->companies()->whereIn('companies.id', $companyIds)->get();

                foreach ($currentCompanies as $company) {
                    if ($company->pivot->status === 'open') {
                        throw new \Exception("Period for company {$company->id} is already open");
                    }
                }

                $syncData = [];
                foreach ($companyIds as $companyId) {
                    if (!in_array($companyId, $currentCompanies->pluck('id')->toArray())) {
                        $syncData[$companyId] = ['status' => 'open'];
                    } else {
                        $period->companies()->updateExistingPivot($companyId, ['status' => 'open']);
                    }
                }

                $period->companies()->sync($syncData, false);
            } else {
                $period = Period::create([
                    'period_name' => $this->selectedYear . getListBulan()[$this->selectedMonth],
                    'year' => $this->selectedYear,
                    'month' => $this->selectedMonth,
                ]);

                foreach ($companyIds as $companyId) {
                    $period->companies()->attach($companyId, ['status' => 'open']);
                }
            }

            foreach ($companyIds as $companyId) {
                $prevYearMonthPeriod = getPrevPeriod($this->selectedYear, $this->selectedMonth);
                $prevPeriod = Period::where('year', $prevYearMonthPeriod[0])
                    ->where('month', $prevYearMonthPeriod[1])
                    ->first();
                if ($prevPeriod) {
                    $prevStocks = StockClosure::where('period_id', $prevPeriod->id)
                        ->where('company_id', $companyId)
                        ->where('trans_type', 'closing')
                        ->get();
                    foreach ($prevStocks as $prevStock) {
                        StockClosure::updateOrCreate(
                            [
                                'period_id' => $period->id,
                                'sloc_id' => $prevStock->sloc_id
                            ],
                            [
                                'company_id' => $companyId,
                                'plant_id' => $prevStock->plant_id,
                                'material_id' => $prevStock->material_id,
                                'material_code' => $prevStock->material_code,
                                'part_no' => $prevStock->part_no,
                                'material_mnemonic' => $prevStock->material_mnemonic,
                                'material_description' => $prevStock->material_description,
                                'uom_id' => $prevStock->uom_id,
                                'qty_soh' => $prevStock->qty_soh,
                                'qty_intransit' => $prevStock->qty_intransit,
                                'trans_type' => 'opening'
                            ]
                        );
                    }
                }
            }

            DB::commit();
            $this->dispatch('success', 'Data has been updated');
            $this->resetPage();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function closePeriod($companyIds)
    {
        $permissions = [
            'close-period',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::beginTransaction();
        try {
            $period = Period::where('year', $this->selectedYear)
                ->where('month', $this->selectedMonth)
                ->first();

            if (!$period) {
                throw new \Exception('Period not found');
            }

            // Pengecekan status period untuk masing-masing company
            $companies = $period->companies()->whereIn('companies.id', $companyIds)->get();
            foreach ($companies as $company) {
                if ($company->pivot->status !== 'open') {
                    throw new \Exception("Period for company {$company->company_name} is not open");
                }
            }

            $material = Material::first();
            $uom = Uom::first();

            foreach ($companyIds as $companyId) {
                $slocs = Sloc::where('company_id', $companyId);
                foreach ($slocs as $sloc) {
                    $parseMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1);

                    $startDate = $parseMonth->startOfMonth();
                    $endDate = $parseMonth->endOfMonth();

                    $openingStock = StockClosure::where('sloc_id', $sloc->id)
                        ->where('period_id', $period->id)
                        ->where('trans_type', 'opening')
                        ->value('qty_soh');

                    $qtyReceipt = Receipt::where('warehouse', $sloc->sloc_code)
                        ->whereBetween('trans_date', [$startDate, $endDate])
                        ->whereNotNull('posting_no')
                        ->sum('qty');

                    $qtyTransfer = ReceiptTransfer::where('from_warehouse', $sloc->sloc_code)
                        ->whereBetween('trans_date', [$startDate, $endDate])
                        ->whereNotNull('posting_no')
                        ->sum('qty');

                    $qtyReceiptTransfer = ReceiptTransfer::where('to_warehouse', $sloc->sloc_code)
                        ->whereBetween('trans_date', [$startDate, $endDate])
                        ->whereNotNull('posting_no')
                        ->sum('qty');

                    $qtyIssue = Issue::where('warehouse', $sloc->sloc_code)
                        ->whereBetween('trans_date', [$startDate, $endDate])
                        ->whereNotNull('posting_no')
                        ->sum('qty');

                    $qtyAdjust = AdjustmentDetail::where('sloc_id', $sloc->id)
                        ->whereHas('header', function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('trans_date', [$startDate, $endDate]);
                        })
                        ->sum('adjust_qty');

                    $closingStock = $openingStock + $qtyReceipt - $qtyTransfer + $qtyReceiptTransfer - $qtyIssue + $qtyAdjust;
                    StockClosure::updateOrCreate(
                        [
                            'period_id' => $period->id,
                            'sloc_id' => $sloc->id,
                            'material_id' => $material->id
                        ],
                        [
                            'company_id' => $companyId,
                            'plant_id' => $sloc->plant_id,
                            'material_code' => $material->material_code,
                            'part_no' => $material->part_no,
                            'material_mnemonic' => $material->material_mnemonic,
                            'material_description' => $material->material_description,
                            'uom_id' => $uom->id,
                            'qty_soh' => $closingStock,
                            'qty_intransit' => 0,
                            'trans_type' => 'closing'
                        ]
                    );
                }
            }
            $period->companies()->updateExistingPivot($companyIds, ['status' => 'close']);

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
