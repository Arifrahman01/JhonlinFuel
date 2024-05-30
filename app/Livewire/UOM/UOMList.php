<?php

namespace App\Livewire\UOM;

use App\Models\Uom;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class UOMList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $q;


    public function render()
    {
        $permissions = [
            'view-master-uom',
            'create-master-uom',
            'edit-master-uom',
            'delete-master-uom',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $uoms = Uom::search($this->q)->paginate(10);
        return view('livewire.u-o-m.u-o-m-list', compact('uoms'));
    }

    public function search()
    {
        $this->resetPage();
    }
}
