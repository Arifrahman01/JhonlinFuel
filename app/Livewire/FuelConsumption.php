<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class FuelConsumption extends Component
{
    use WithPagination;
    public $startDate;
    public $endDate;

    protected $paginationTheme = 'bootstrap';

    public function mount(){
        $this->startDate = $this->startDate ?? date('Y-m-d', strtotime("-30 days"));
        $this->endDate = $this->endDate ?? date('Y-m-d');
    }

    public function render()
    {
        $consumptions = DB::select('CALL sp_report_fuel_comsumtion(?, ?)', [$this->startDate, $this->endDate]);;
        return view('livewire.fuel-consumption', compact('consumptions'));
    }
    public function search()
    {
        $this->resetPage();
    }

}
