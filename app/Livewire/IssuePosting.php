<?php

namespace App\Livewire;

use App\Exports\IssueExport;
use App\Models\Company;
use App\Models\Issue;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class IssuePosting extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $start_date;
    public $end_date;
    public $companies = [];
    public $c;

    public function mount()
    {
        $this->start_date = $this->start_date ?? date('Y-m-d', strtotime("-30 days"));
        $this->end_date = $this->end_date ?? date('Y-m-d');
        $this->companies = Company::all();
    }

    public function render()
    {
        $issues = Issue::with(['company','departments','fuelmans','plants','slocs','equipments','activitys','materials'])->whereBetween('trans_date', [$this->start_date, $this->end_date])
        ->when($this->c, fn ($query, $c) => $query->where('company_code', $c))
        ->whereNotNull('posting_no')
        ->latest()->paginate(10);
        return view('livewire.issue-posting', ['issues' => $issues]);
    }

    public function report($company, $start, $end)
    {
        try {            
            $fileName = 'Issue ' . str_replace(['/', '\\'], '_', $start) . ' s-d ' . str_replace(['/', '\\'], '_', $end) . '.xlsx';

            return Excel::download(new IssueExport($company,$start,$end), $fileName, \Maatwebsite\Excel\Excel::XLSX);

        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
       
    }
    public function search()
    {
        $this->resetPage();
    }
}
