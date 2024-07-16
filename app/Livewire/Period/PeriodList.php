<?php

namespace App\Livewire\Period;

use App\Models\Adjustment\AdjustmentDetail;
use App\Models\Company;
use App\Models\Issue;
use App\Models\Material\Material;
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

    // public $periodId, $periodName, $startDate, $endDate;

    public $q;

    public function mount()
    {
        $this->selectedYear = date('Y');
        $this->selectedMonth = date('n');
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

        $this->periodCompanies = Company::with(['periods' => function ($query) {
            $query->select('periods.id', 'periods.period_name', 'periods.year', 'periods.month', 'company_period.status')
                ->where('periods.year', $this->selectedYear)
                ->where('periods.month', $this->selectedMonth);
        }])->get(['companies.id', 'companies.company_code', 'companies.company_name']);

        return view('livewire.period.period-list', [
            'years' => getListTahun(),
            'months' => getListBulan(),
            'periods' => $periods,
        ]);
    }

    public function search()
    {
        $this->resetPage();
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
                        throw new \Exception("Period for company {$company->company_name} is already open");
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
                    'period_name' => getListBulan()[$this->selectedMonth] . ' ' . $this->selectedYear,
                    'year' => $this->selectedYear,
                    'month' => $this->selectedMonth,
                ]);

                foreach ($companyIds as $companyId) {
                    $period->companies()->attach($companyId, ['status' => 'open']);
                }
            }

            // $material = Material::first();
            // $uom = Uom::first();

            // $prevYearMonthPeriod = getPrevPeriod($this->selectedYear, $this->selectedMonth);
            // $prevPeriod = Period::where('year', $prevYearMonthPeriod[0])
            //     ->where('month', $prevYearMonthPeriod[1])
            //     ->first();

            // foreach ($companyIds as $companyId) {
            //     if ($prevPeriod) {
            //         $slocs = Sloc::leftJoin('stock_closures', function ($join) use ($prevPeriod) {
            //             $join->on('stock_closures.sloc_id', '=', 'storage_locations.id')
            //                 ->where('stock_closures.period_id', '=', $prevPeriod->id)
            //                 ->where('stock_closures.trans_type', '=', 'closing')
            //                 ->whereNull('stock_closures.deleted_at');
            //         })
            //             ->where('storage_locations.company_id', $companyId)
            //             ->select(
            //                 'storage_locations.id',
            //                 'storage_locations.plant_id',
            //                 'storage_locations.sloc_code',
            //                 'storage_locations.sloc_name',
            //                 DB::raw('IFNULL(stock_closures.qty_soh, 0) as qty_soh'),
            //                 DB::raw('IFNULL(stock_closures.qty_intransit, 0) as qty_intransit'),
            //                 'stock_closures.period_id'
            //             )
            //             ->get();

            //         foreach ($slocs as $sloc) {
            //             StockClosure::updateOrCreate(
            //                 [
            //                     'period_id' => $period->id,
            //                     'sloc_id' => $sloc->id,
            //                     'trans_type' => 'opening',
            //                 ],
            //                 [
            //                     'company_id' => $companyId,
            //                     'plant_id' => $sloc->plant_id,
            //                     'material_id' => $material->id,
            //                     'material_code' => $material->material_code,
            //                     'part_no' => $material->part_no,
            //                     'material_mnemonic' => $material->material_mnemonic,
            //                     'material_description' => $material->material_description,
            //                     'uom_id' => $uom->id,
            //                     'qty_soh' => $sloc->qty_soh,
            //                     'qty_intransit' => $sloc->qty_intransit,
            //                 ]
            //             );
            //         }
            //     } else {
            //         $slocs = Sloc::where('company_id', $companyId)
            //             ->get();

            //         foreach ($slocs as $sloc) {
            //             StockClosure::updateOrCreate(
            //                 [
            //                     'period_id' => $period->id,
            //                     'sloc_id' => $sloc->id
            //                 ],
            //                 [
            //                     'company_id' => $companyId,
            //                     'plant_id' => $sloc->plant_id,
            //                     'material_id' => $material->id,
            //                     'material_code' => $material->material_code,
            //                     'part_no' => $material->part_no,
            //                     'material_mnemonic' => $material->material_mnemonic,
            //                     'material_description' => $material->material_description,
            //                     'uom_id' => $uom->id,
            //                     'qty_soh' => 0,
            //                     'qty_intransit' => 0,
            //                     'trans_type' => 'opening'
            //                 ]
            //             );
            //         }
            //     }
            // }


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

            $prevPeriodYM = getPrevPeriod($this->selectedYear, $this->selectedMonth);
            $prevYear = $prevPeriodYM[0];
            $prevMonth = $prevPeriodYM[1];
            $prevPeriod = Period::where('year', $prevYear)
                ->where('month', $prevMonth)
                ->first();

            // Pengecekan status period untuk masing-masing company
            $companies = $period->companies()->whereIn('companies.id', $companyIds)->get();
            foreach ($companies as $company) {
                if ($company->pivot->status !== 'open') {
                    throw new \Exception("Period for company {$company->company_name} is not open");
                }

                $transaksiGantung = $this->cekTransaksi($company->company_code);
                if ($transaksiGantung) {
                    throw new \Exception($transaksiGantung);
                }
            }

            $material = Material::first();
            $uom = Uom::first();

            foreach ($companyIds as $companyId) {
                $slocs = Sloc::where('company_id', $companyId)
                    ->get();
                foreach ($slocs as $sloc) {
                    $this->closingPerSloc($prevPeriod, $period, $sloc, $material, $uom);
                    // $openingStock = 0;
                    // if ($prevPeriod) {
                    //     $openingStock = StockClosure::where('sloc_id', $sloc->id)
                    //         ->where('period_id', $prevPeriod->id)
                    //         ->where('trans_type', 'closing')
                    //         ->value('qty_soh');
                    // }

                    // $qtyReceipt = Receipt::where('warehouse', $sloc->sloc_code)
                    //     ->whereYear('trans_date', $this->selectedYear)
                    //     ->whereMonth('trans_date', $this->selectedMonth)
                    //     ->whereNotNull('posting_no')
                    //     ->sum('qty');

                    // $qtyTransfer = ReceiptTransfer::where('from_warehouse', $sloc->sloc_code)
                    //     ->whereYear('trans_date', $this->selectedYear)
                    //     ->whereMonth('trans_date', $this->selectedMonth)
                    //     ->whereNotNull('posting_no')
                    //     ->sum('qty');

                    // $qtyReceiptTransfer = ReceiptTransfer::where('to_warehouse', $sloc->sloc_code)
                    //     ->whereYear('trans_date', $this->selectedYear)
                    //     ->whereMonth('trans_date', $this->selectedMonth)
                    //     ->whereNotNull('posting_no')
                    //     ->sum('qty');

                    // $qtyIssue = Issue::where('warehouse', $sloc->sloc_code)
                    //     ->whereYear('trans_date', $this->selectedYear)
                    //     ->whereMonth('trans_date', $this->selectedMonth)
                    //     ->whereNotNull('posting_no')
                    //     ->sum('qty');

                    // $qtyAdjust = AdjustmentDetail::where('sloc_id', $sloc->id)
                    //     ->whereHas('header', function ($query) {
                    //         $query->whereYear('adjustment_date', $this->selectedYear)
                    //             ->whereMonth('adjustment_date', $this->selectedMonth);
                    //     })
                    //     ->sum('adjust_qty');

                    // $closingStock = $openingStock + $qtyReceipt - $qtyTransfer + $qtyReceiptTransfer - $qtyIssue + $qtyAdjust;
                    // StockClosure::updateOrCreate(
                    //     [
                    //         'period_id' => $period->id,
                    //         'sloc_id' => $sloc->id,
                    //         'material_id' => $material->id,
                    //         'trans_type' => 'closing'
                    //     ],
                    //     [
                    //         'company_id' => $companyId,
                    //         'plant_id' => $sloc->plant_id,
                    //         'material_code' => $material->material_code,
                    //         'part_no' => $material->part_no,
                    //         'material_mnemonic' => $material->material_mnemonic,
                    //         'material_description' => $material->material_description,
                    //         'uom_id' => $uom->id,
                    //         'qty_soh' => $closingStock,
                    //         'qty_intransit' => 0,
                    //     ]
                    // );

                    $formattedDate = sprintf('%04d-%02d-01', $this->selectedYear, $this->selectedMonth);

                    $nextPeriods = Period::where(DB::raw("CONCAT(year, '-', LPAD(month, 2, '0'), '-01')"), '>', $formattedDate)
                        ->orderBy('year')
                        ->orderBy('month')
                        ->get();
                    // $nextPeriods = Period::where('year', '>', $this->selectedYear)
                    //     ->where('month', '>', $this->selectedMonth)
                    //     ->orderBy('year')
                    //     ->orderBy('month')
                    //     ->get();

                    $prevPeriod_ = $period;
                    foreach ($nextPeriods as $nextPeriod) {
                        $this->closingPerSloc($prevPeriod_, $nextPeriod, $sloc, $material, $uom);
                        $prevPeriod_ = $nextPeriod;
                    }
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

    private function cekTransaksi($companyCode)
    {
        $receiptBelumPosting = Receipt::where('company_code', $companyCode)
            ->whereYear('trans_date', $this->selectedYear)
            ->whereMonth('trans_date', $this->selectedMonth)
            ->whereNull('posting_no')
            ->exists();
        $transferBelumPostingFrom = Transfer::where('from_company_code', $companyCode)
            ->whereYear('trans_date', $this->selectedYear)
            ->whereMonth('trans_date', $this->selectedMonth)
            ->whereNull('posting_no')
            ->exists();
        $transferBelumPostingTo = Transfer::where('to_company_code', $companyCode)
            ->whereYear('trans_date', $this->selectedYear)
            ->whereMonth('trans_date', $this->selectedMonth)
            ->whereNull('posting_no')
            ->exists();
        $receiptTransferBelumPostingFrom = ReceiptTransfer::where('from_company_code', $companyCode)
            ->whereYear('trans_date', $this->selectedYear)
            ->whereMonth('trans_date', $this->selectedMonth)
            ->whereNull('posting_no')
            ->exists();
        $receiptTransferBelumPostingTo = ReceiptTransfer::where('to_company_code', $companyCode)
            ->whereYear('trans_date', $this->selectedYear)
            ->whereMonth('trans_date', $this->selectedMonth)
            ->whereNull('posting_no')
            ->exists();
        $issueBelumPosting = Issue::where('company_code', $companyCode)
            ->whereYear('trans_date', $this->selectedYear)
            ->whereMonth('trans_date', $this->selectedMonth)
            ->whereNull('posting_no')
            ->exists();
        // $osTransfer = MaterialStock::where('company_id', $companyId)
        //     ->whereNotNull('qty_intransit')
        //     ->where('qty_intransit', '!=', 0)
        //     ->exists();

        if ($receiptBelumPosting) {
            return 'Ada Receipt PO yang belum posting';
        }

        if ($transferBelumPostingFrom) {
            return 'Ada Transfer yang belum posting';
        }

        if ($transferBelumPostingTo) {
            return 'Ada Transfer yang belum posting';
        }

        if ($receiptTransferBelumPostingFrom) {
            return 'Ada Receipt Transfer yang belum posting';
        }

        if ($receiptTransferBelumPostingTo) {
            return 'Ada Receipt Transfer yang belum posting';
        }

        if ($issueBelumPosting) {
            return 'Ada Issue yang belum posting';
        }

        // if ($osTransfer) {
        //     return 'Ada Transfer yang belum diReceipt Transfer';
        // }

        return false;
    }

    private function closingPerSloc($prevPeriod, $nextPeriod, $sloc, $material, $uom)
    {
        $year_ = $nextPeriod->year;
        $month_ = $nextPeriod->month;
        $openingStock = 0;
        if ($prevPeriod) {
            $openingStock = StockClosure::where('sloc_id', $sloc->id)
                ->where('period_id', $prevPeriod->id)
                ->where('trans_type', 'closing')
                ->value('qty_soh');
        }

        $qtyReceipt = Receipt::where('warehouse', $sloc->sloc_code)
            ->whereYear('trans_date', $year_)
            ->whereMonth('trans_date', $month_)
            ->whereNotNull('posting_no')
            ->sum('qty');

        $qtyTransfer = ReceiptTransfer::where('from_warehouse', $sloc->sloc_code)
            ->whereYear('trans_date', $year_)
            ->whereMonth('trans_date', $month_)
            ->whereNotNull('posting_no')
            ->sum('qty');

        $qtyReceiptTransfer = ReceiptTransfer::where('to_warehouse', $sloc->sloc_code)
            ->whereYear('trans_date', $year_)
            ->whereMonth('trans_date', $month_)
            ->whereNotNull('posting_no')
            ->sum('qty');

        $qtyIssue = Issue::where('warehouse', $sloc->sloc_code)
            ->whereYear('trans_date', $year_)
            ->whereMonth('trans_date', $month_)
            ->whereNotNull('posting_no')
            ->sum('qty');

        $qtyAdjust = AdjustmentDetail::where('sloc_id', $sloc->id)
            ->whereHas('header', function ($query) use ($year_, $month_) {
                $query->whereYear('adjustment_date', $year_)
                    ->whereMonth('adjustment_date', $month_);
            })
            ->sum('adjust_qty');

        $closingStock = $openingStock + $qtyReceipt - $qtyTransfer + $qtyReceiptTransfer - $qtyIssue + $qtyAdjust;
        StockClosure::updateOrCreate(
            [
                'period_id' => $nextPeriod->id,
                'sloc_id' => $sloc->id,
                'material_id' => $material->id,
                'trans_type' => 'closing'
            ],
            [
                'company_id' => $sloc->company_id,
                'plant_id' => $sloc->plant_id,
                'material_code' => $material->material_code,
                'part_no' => $material->part_no,
                'material_mnemonic' => $material->material_mnemonic,
                'material_description' => $material->material_description,
                'uom_id' => $uom->id,
                'qty_soh' => $closingStock,
                'qty_intransit' => 0,
            ]
        );
    }
}
