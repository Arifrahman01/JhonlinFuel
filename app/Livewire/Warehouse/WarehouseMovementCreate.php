<?php

namespace App\Livewire\Warehouse;

use App\Models\Company;
use Livewire\Component;

class WarehouseMovementCreate extends Component
{
    public $loading = false;
    public $companies = [];

    public function mount()
    {
        $this->companies = Company::all();
    }
    public function render()
    {
        return view('livewire.warehouse.warehouse-movement-create');
    }
}
