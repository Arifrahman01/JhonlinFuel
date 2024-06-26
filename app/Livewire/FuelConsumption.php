<?php

namespace App\Livewire;

use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class FuelConsumption extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $allConsumptions;
    public $page = 1;
    public $perPage = 10;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->startDate = $this->startDate ?? date('Y-m-d', strtotime("-30 days"));
        $this->endDate = $this->endDate ?? date('Y-m-d');
    }
    public function render()
    {
        $page = $this->page;
        $perPage = $this->perPage;
        $totalRecords = 0;
        $reports = DB::select('CALL sp_report_fuel_comsumtion(?, ?, ?, ?, @totalRecords)', [$this->startDate, $this->endDate, $page, $perPage]);
        $totalRecords = DB::select('SELECT @totalRecords AS totalRecords')[0]->totalRecords;
        $consumptions = new \Illuminate\Pagination\LengthAwarePaginator(
            $reports, 
            $totalRecords, 
            $perPage, 
            $page, 
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return view('livewire.fuel-consumption', compact('consumptions'));
    }



    public function search()
    {
        $this->resetPage();
    }

}
