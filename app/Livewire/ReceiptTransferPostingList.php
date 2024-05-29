<?php

namespace App\Livewire;

use App\Exports\ReceiptTransferExport;
use App\Models\Company;
use App\Models\ReceiptTransfer;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class ReceiptTransferPostingList extends Component
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
        // $userCompanyCode = Company::find(auth()->user()->company_id)->company_code;
        $rcvTransfers = ReceiptTransfer::search($this->q)->with(['fromCompany','toCompany','fromWarehouse','toWarehouse','materials','equipments'])
        ->when($this->c, function ($query, $c) {
            $query->where(function ($query) use ($c) {
                $query->where('from_company_code', $c)
                      ->orWhere('to_company_code', $c);
            });
        })
        ->whereBetween('trans_date', [$this->start_date, $this->end_date])->orderBy('id','desc')->latest()->paginate(10);
        return view('livewire.receipt-transfer-posting-list', compact('rcvTransfers'));
    }
    
    public function report($search, $company, $start, $end)
    {
        try {  
            $fileName = 'Transfer ' . str_replace(['/', '\\'], '_', $start) . ' s-d ' . str_replace(['/', '\\'], '_', $end) . '.xlsx';
            return Excel::download(new ReceiptTransferExport($search, $company, $start, $end), $fileName, \Maatwebsite\Excel\Excel::XLSX);
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

}
