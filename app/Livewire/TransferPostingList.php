<?php

namespace App\Livewire;

use App\Exports\TransferExport;
use App\Models\Company;
use App\Models\Transfer;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class TransferPostingList extends Component
{
    use WithPagination;
    public $start_date;
    public $end_date;
    public $companies = [];
    public $c, $q;

    protected $paginationTheme = 'bootstrap';

    public function mount(){
        $this->start_date = $this->start_date ?? date('Y-m-d', strtotime("-30 days"));
        $this->end_date = $this->end_date ?? date('Y-m-d');
        $this->companies = Company::all();
    }

    public function render()
    {
        $transfers = Transfer::search($this->q)->with(['fromCompany','toCompany','fromSloc','toSloc','materials','equipments'])
        ->when($this->c, function ($query, $c) {
            $query->where(function ($query) use ($c) {
                $query->where('from_company_code', $c)
                      ->orWhere('to_company_code', $c);
            });
        })
        ->whereBetween('trans_date', [$this->start_date, $this->end_date])->orderBy('id','desc')->latest()->paginate(10);
        return view('livewire.transfer-posting-list', ['transfers' => $transfers]);
    }
    public function search()
    {
        $this->resetPage();
    }

    public function report($search, $company, $start, $end)
    {
        try {  
            $fileName = 'Transfer ' . str_replace(['/', '\\'], '_', $start) . ' s-d ' . str_replace(['/', '\\'], '_', $end) . '.xlsx';
            return Excel::download(new TransferExport($search, $company, $start, $end), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
