<?php

namespace App\Livewire\UOM;

use App\Models\Uom;
use Livewire\Component;
use Livewire\WithPagination;

class UOMList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $q;

    
    public function render()
    {
        $uoms = Uom::search($this->q)->paginate(10);
        return view('livewire.u-o-m.u-o-m-list', compact('uoms'));
    }

    public function search()
    {
        $this->resetPage();
    }
}
