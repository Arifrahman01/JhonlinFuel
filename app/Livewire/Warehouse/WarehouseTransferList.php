<?php

namespace App\Livewire\Warehouse;

use App\Models\SlocTransfer;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class WarehouseTransferList extends Component
{
    
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $q;
    
    public function render()
    {
        $slocTransfers = SlocTransfer::paginate(10);
        return view('livewire.warehouse.warehouse-transfer-list',compact('slocTransfers'));
    }
}
