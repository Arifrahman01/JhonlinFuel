<?php

namespace App\Livewire;

use App\Exports\FuelDistributionExport;
use App\Models\Adjustment\AdjustmentDetail;
use App\Models\Company;
use App\Models\Issue;
use App\Models\Period;
use App\Models\Receipt;
use App\Models\ReceiptTransfer;
use App\Models\StockClosure;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class FuelDistribution extends Component
{
    use WithPagination;
    public $periodId;
    public $distributions = [];
    public function mount()
    {
        $period = Period::latest()->first();
        if ($period) {
            $this->periodId = $period->value('id');
        }
    }

    public function render()
    {
        $periods = Period::all();
        if ($this->periodId) {
            $period = Period::find($this->periodId);
            if ($period) {
                $openingQtySubquery = StockClosure::select('company_id', DB::raw('SUM(qty_soh) as opening_qty'))
                    ->where('trans_type', 'opening')
                    ->where('period_id', $this->periodId)
                    ->whereNull('deleted_at')
                    ->groupBy('company_id');

                $rcvQtySubquery = Receipt::select('company_code', DB::raw('SUM(qty) as rcv_qty'))
                    ->where('trans_type', 'RCV')
                    ->whereYear('trans_date', $period->year)
                    ->whereMonth('trans_date', $period->month)
                    ->whereNotNull('posting_no')
                    ->groupBy('company_code');

                $trfQtySubquery = ReceiptTransfer::select('from_company_code', DB::raw('SUM(qty) as trf_qty'))
                    ->where('trans_type', 'RCT')
                    ->whereYear('trans_date', $period->year)
                    ->whereMonth('trans_date', $period->month)
                    ->whereNotNull('posting_no')
                    ->groupBy('from_company_code');

                $rcvTrfQtySubquery = ReceiptTransfer::select('to_company_code', DB::raw('SUM(qty) as rcvTrf_qty'))
                    ->where('trans_type', 'RCT')
                    ->whereYear('trans_date', $period->year)
                    ->whereMonth('trans_date', $period->month)
                    ->whereNotNull('posting_no')
                    ->groupBy('to_company_code');

                $issuedQtySubquery = Issue::select('company_code', DB::raw('SUM(qty) as issued_qty'))
                    ->where('trans_type', 'ISS')
                    ->whereYear('trans_date', $period->year)
                    ->whereMonth('trans_date', $period->month)
                    ->whereNotNull('posting_no')
                    ->groupBy('company_code');

                $adjustmentSubQuery = AdjustmentDetail::select('adjustment_details.company_id', DB::raw('SUM(adjustment_details.adjust_qty) as adjust_qty'))
                    ->leftJoin('adjustment_headers', 'adjustment_details.header_id', '=', 'adjustment_headers.id')
                    ->whereYear('adjustment_headers.adjustment_date', $period->year)
                    ->whereMonth('adjustment_headers.adjustment_date', $period->month)
                    ->groupBy('adjustment_details.company_id');

                $closingQtySubquery = StockClosure::select('company_id', DB::raw('SUM(qty_soh) as closing_qty'))
                    ->where('trans_type', 'closing')
                    ->where('period_id', $this->periodId)
                    ->whereNull('deleted_at')
                    ->groupBy('company_id');

                $this->distributions = Company::leftJoinSub($openingQtySubquery, 'b', 'companies.id', '=', 'b.company_id')
                    ->leftJoinSub($rcvQtySubquery, 'c', 'companies.company_code', '=', 'c.company_code')
                    ->leftJoinSub($trfQtySubquery, 'd', 'companies.company_code', '=', 'd.from_company_code')
                    ->leftJoinSub($rcvTrfQtySubquery, 'e', 'companies.company_code', '=', 'e.to_company_code')
                    ->leftJoinSub($issuedQtySubquery, 'f', 'companies.company_code', '=', 'f.company_code')
                    ->leftJoinSub($adjustmentSubQuery, 'g', 'companies.id', '=', 'g.company_id')
                    ->leftJoinSub($closingQtySubquery, 'h', 'companies.id', '=', 'h.company_id')
                    ->whereNull('companies.deleted_at')
                    ->select(
                        'companies.company_name',
                        DB::raw('IFNULL(b.opening_qty, 0) as opening_qty'),
                        DB::raw('IFNULL(c.rcv_qty, 0) as rcv_qty'),
                        DB::raw('IFNULL(d.trf_qty, 0) as trf_qty'),
                        DB::raw('IFNULL(e.rcvTrf_qty, 0) as rcvTrf_qty'),
                        DB::raw('IFNULL(f.issued_qty, 0) as issued_qty'),
                        DB::raw('IFNULL(g.adjust_qty, 0) as adjust_qty'),
                        DB::raw('IFNULL(h.closing_qty, 0) as closing_qty')
                    )
                    ->get();
            }
        }
        return view('livewire.fuel-distribution', compact('periods'));
    }

    public function report()
    {
        try {
            $period = Period::find($this->periodId);
            $fileName = 'Fuel Distribution ' . $period->period_name . '.xlsx';

            return Excel::download(new FuelDistributionExport($this->periodId), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function search()
    {
        $this->resetPage();
    }
}
