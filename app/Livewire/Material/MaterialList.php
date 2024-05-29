<?php

namespace App\Livewire\Material;

use App\Models\Material\Material;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $q;

    
    public function render()
    {
        $materials = Material::search($this->q)->paginate(10);
        return view('livewire.material.material-list',compact('materials'));
    }
    public function search()
    {
        $this->resetPage();
    }
}
