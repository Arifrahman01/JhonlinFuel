<?php

namespace App\Livewire\Adjustment;

use App\Exports\AdjusmentExport;
use App\Models\Adjustment\AdjustmentHeader;
use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class AdjustmentList extends Component
{
    use WithPagination;

    public $adjNo;
    public $title;

    public $start_date;
    public $end_date;
    public $c;
    public $companies = [];

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public function mount()
    {
        $this->start_date = $this->start_date ?? date('Y-m-d', strtotime("-30 days"));
        $this->end_date = $this->end_date ?? date('Y-m-d');
        $this->companies = Company::all();
    }

    public function render()
    {
        $adjusts = AdjustmentHeader::with([
            'details.plant',
            'details.sloc',
            'company',
        ])
            ->when($this->c, fn ($query, $c) => $query->where('company_id', $c))
            ->whereBetween('adjustment_date', [$this->start_date, $this->end_date])
            ->search(['adjNo' => $this->adjNo])
            ->latest()
            ->paginate(10);
        return view('livewire.adjustment.adjustment-list', compact('adjusts'));
    }

    public function report($search ,$company, $start, $end)
    {
        try {            
            $fileName = 'Adjusment ' . str_replace(['/', '\\'], '_', $start) . ' s-d ' . str_replace(['/', '\\'], '_', $end) . '.xlsx';

            return Excel::download(new AdjusmentExport($search, $company,$start,$end), $fileName, \Maatwebsite\Excel\Excel::XLSX);

        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
       
    }

    public function search()
    {
        $this->resetPage();
    }
}
