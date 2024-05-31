<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class FuelConsumption extends Component
{
    use WithPagination;
    public $start_date;
    public $end_date;

    protected $paginationTheme = 'bootstrap';

    public function mount(){
        $this->start_date = $this->start_date ?? date('Y-m-d', strtotime("-30 days"));
        $this->end_date = $this->end_date ?? date('Y-m-d');
    }

    public function render()
    {
        $consumptions = DB::select('CALL sp_report_fuel_comsumtion(?, ?)', [$this->start_date, $this->end_date]);;
        return view('livewire.fuel-consumption', compact('consumptions'));
    }
    public function search()
    {
        $this->resetPage();
    }

}
