<?php

namespace App\Livewire;

use App\Exports\IssueExport;
use App\Models\Company;
use App\Models\Issue;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class IssuePosting extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $start_date;
    public $end_date;
    public $companies = [];
    public $c , $q;

    public function mount()
    {
        $this->start_date = $this->start_date ?? date('Y-m-d', strtotime("-30 days"));
        $this->end_date = $this->end_date ?? date('Y-m-d');
        $this->companies = Company::allowed( 'view-transaksi-issue')->get();
    }

    public function render()
    {
        $permissions = [
            'view-transaksi-issue'
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $issues = Issue::with(['company','departments','fuelmans','plants','slocs','equipments','activitys','materials'])->whereBetween('trans_date', [$this->start_date, $this->end_date])
        ->when($this->c, fn ($query, $c) => $query->where('company_code', $c))
        ->whereNotNull('posting_no')
        ->latest()->paginate(10);
        return view('livewire.issue-posting', ['issues' => $issues]);
    }

    public function report($company, $start, $end)
    {
        try {    
            ini_set('memory_limit', '1G'); 
            ini_set('max_execution_time', 120);

            $fileName = 'Issue ' . $start . ' . ' . $end . '.xlsx';

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
