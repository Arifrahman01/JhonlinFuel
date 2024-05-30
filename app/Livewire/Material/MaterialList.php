<?php

namespace App\Livewire\Material;

use App\Models\Material\Material;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class MaterialList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $q;


    public function render()
    {
        $permissions = [
            'view-master-material',
            'create-master-material',
            'edit-master-material',
            'delete-master-material',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $materials = Material::search($this->q)->paginate(10);
        return view('livewire.material.material-list', compact('materials'));
    }
    public function search()
    {
        $this->resetPage();
    }
}
