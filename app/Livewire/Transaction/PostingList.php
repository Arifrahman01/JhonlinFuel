<?php

namespace App\Livewire\Transaction;

use App\Exports\issueExport;
use App\Models\Company;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class PostingList extends Component
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
        $transactions = Transaction::with('company')->whereBetween('trans_date', [$this->start_date, $this->end_date])
            ->when($this->c, fn ($query, $c) => $query->where('company_id', $c))
            ->latest()
            ->paginate(10);
        return view('livewire.transaction.posting-list', ['transactions' => $transactions]);
    }
    public function search()
    {
        $this->resetPage();
    }

    public function report($company, $start, $end)
    {
        try {
            // $transactions = Transaction::whereBetween('trans_date', [$b, $c])
            // ->when($a, fn ($query, $c) => $query->where('company_id', $c))
            // ->latest()->get();
            
            $fileName = 'Issue ' . str_replace(['/', '\\'], '_', $start) . ' s-d ' . str_replace(['/', '\\'], '_', $end) . '.xlsx';

            return Excel::download(new IssueExport($company,$start,$end), $fileName, \Maatwebsite\Excel\Excel::XLSX);

        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
       
    }
}
