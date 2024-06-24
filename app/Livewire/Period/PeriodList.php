<?php

namespace App\Livewire\Period;

use App\Models\Company;
use App\Models\Issue;
use App\Models\Material\MaterialMovement;
use App\Models\Material\MaterialStock;
use App\Models\Period;
use App\Models\Receipt;
use App\Models\ReceiptTransfer;
use App\Models\Sloc;
use App\Models\StockClosure;
use App\Models\Transfer;
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
                // Dapatkan semua companyId yang saat ini terkait dengan $period
                $currentCompanyIds = $period->companies()->pluck('id')->toArray();

                // Siapkan array untuk sinkronisasi
                $syncData = [];
                foreach ($companyIds as $companyId) {
                    if (!in_array($companyId, $currentCompanyIds)) {
                        $syncData[$companyId] = ['status' => 'open'];
                    } else {
                        $period->companies()->updateExistingPivot($companyId, ['status' => 'open']);
                    }
                }

                // Sinkronkan kembali hubungan dengan array baru yang telah diupdate
                $period->companies()->sync($syncData, false); // false to not detach existing ones
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
                $prevStocks = StockClosure::where('period_id', $prevPeriod->id)
                    ->where('company_id', $companyId)
                    ->where('trans_type', 'closing')
                    ->get();
                foreach ($prevStocks as $prevStock) {
                    StockClosure::create([
                        'period_id' => $period->id,
                        'company_id' => $companyId,
                        'plant_id' => $prevStock->plant_id,
                        'sloc_id' => $prevStock->sloc_id,
                        'material_id' => $prevStock->material_id,
                        'material_code' => $prevStock->material_code,
                        'part_no' => $prevStock->part_no,
                        'material_mnemonic' => $prevStock->material_mnemonic,
                        'material_description' => $prevStock->material_description,
                        'uom_id' => $prevStock->uom_id,
                        'qty_soh' => $prevStock->qty_soh,
                        'qty_intransit' => $prevStock->qty_intransit,
                        'trans_type' => 'opening',
                    ]);
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
            $period->companies()->updateExistingPivot($companyIds, ['status' => 'close']);

            foreach ($companyIds as $companyId) {
                $slocs = Sloc::where('company_id', $companyId);
                foreach ($slocs as $sloc) {
                    $parseMonth = Carbon::create($this->selectedYear, $this->selectedMonth, 1);

                    $startDate = $parseMonth->startOfMonth();
                    $endDate = $parseMonth->endOfMonth();

                    $qtyReceipt = MaterialMovement::where('sloc_id', $sloc->id)
                        ->where('movement_type', 'RCV')
                        ->whereBetween('movement_date', [$startDate, $endDate])
                        ->sum('qty');

                    $qtyTransfer = MaterialMovement::where('sloc_id', $sloc->id);


                    $qtyReceiptTransfer = MaterialMovement::where('sloc_id', $sloc->id)
                        ->where('movement_type', 'ISS')
                        ->whereBetween('movement_date', [$startDate, $endDate])
                        ->sum('qty');

                    $qtyIssue = MaterialMovement::where('sloc_id', $sloc->id)
                        ->where('movement_type', 'ISS')
                        ->whereBetween('movement_date', [$startDate, $endDate])
                        ->sum('qty');
                }
                $prevYearMonthPeriod = getPrevPeriod($this->selectedYear, $this->selectedMonth);
                $prevPeriod = Period::where('year', $prevYearMonthPeriod[0])
                    ->where('month', $prevYearMonthPeriod[1])
                    ->first();
                $prevStocks = StockClosure::where('period_id', $prevPeriod->id)
                    ->where('company_id', $companyId)
                    ->where('trans_type', 'closing')
                    ->get();
                foreach ($prevStocks as $prevStock) {
                    StockClosure::create([
                        'period_id' => $period->id,
                        'company_id' => $companyId,
                        'plant_id' => $prevStock->plant_id,
                        'sloc_id' => $prevStock->sloc_id,
                        'material_id' => $prevStock->material_id,
                        'material_code' => $prevStock->material_code,
                        'part_no' => $prevStock->part_no,
                        'material_mnemonic' => $prevStock->material_mnemonic,
                        'material_description' => $prevStock->material_description,
                        'uom_id' => $prevStock->uom_id,
                        'qty_soh' => $prevStock->qty_soh,
                        'qty_intransit' => $prevStock->qty_intransit,
                        'trans_type' => 'opening',
                    ]);
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
