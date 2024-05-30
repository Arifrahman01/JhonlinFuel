<?php

namespace App\Livewire;

use App\Exports\ReceiptExport;
use App\Models\Company;
use App\Models\Receipt;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

class ReceiptPostingList extends Component
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
        $permissions = [
            'view-transaksi-receipt-po'
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $receipts = Receipt::search($this->q)->with(['company','plants','slocs','materials','equipments'])
        ->when($this->c, fn ($query, $c) => $query->where('company_code', $c))
        ->whereNotNull('posting_no')
        ->whereBetween('trans_date', [$this->start_date, $this->end_date])->latest()->paginate(10);
        return view('livewire.receipt-posting-list', compact('receipts'));
    }

    public function report($search, $company, $start, $end)
    {
        try {  
            $fileName = 'Receipt ' . str_replace(['/', '\\'], '_', $start) . ' s-d ' . str_replace(['/', '\\'], '_', $end) . '.xlsx';
            return Excel::download(new ReceiptExport($search, $company, $start, $end), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
    public function search()
    {
        $this->resetPage();
    }


}
