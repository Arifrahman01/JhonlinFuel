<?php

namespace App\Livewire\Fuelman;

use App\Models\Company;
use App\Models\Fuelman;
use App\Models\Plant;
use Livewire\Component;
use Livewire\WithPagination;

class FuelmanList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public $plants = [];

    public $c, $p, $q;
    public function render()
    {
        $companies = Company::all();
        $fuelmans = Fuelman::with(['company', 'plant'])
            ->when($this->c, fn ($query, $c) => $query->where('company_id', $c))
            ->when($this->p, fn ($query, $p) => $query->where('plant_id', $p))
            ->when($this->q, fn ($query, $q) => $query->where(fn ($query) =>
            $query->where('name', 'like', '%' . $q . '%')
                ->orWhere('nik', 'like', '%' . $q . '%')))
            ->latest()
            ->paginate(10);
        return view('livewire.fuelman.fuelman-list', compact('companies', 'fuelmans'));
    }

    public function updatedC($value)
    {
        $this->plants = Plant::where('company_id', $value)
            ->get();
    }

    public function search()
    {
        $this->resetPage();
    }
}
