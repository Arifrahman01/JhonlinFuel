<?php

namespace App\Livewire;

use App\Models\Transfer;
use Livewire\Component;
use Livewire\WithPagination;

class TransferPostingList extends Component
{
    use WithPagination;
    public $filter_date;
    public $filter_search;

    protected $paginationTheme = 'bootstrap';

    public function mount(){
        $this->filter_date = date('Y-m');
    }

    public function render()
    {
        $start_date = $this->filter_date . '-01';
        $end_date = date('Y-m-t', strtotime($start_date)); 
        $transfers = Transfer::search($this->filter_search)->whereBetween('trans_date', [$start_date, $end_date])->orderBy('id','desc')->paginate(10);
        return view('livewire.transfer-posting-list', ['transfers' => $transfers]);
    }
    public function search()
    {
        $this->resetPage();
    }
}
