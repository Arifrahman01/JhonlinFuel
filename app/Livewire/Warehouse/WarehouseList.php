<?php

namespace App\Livewire\Warehouse;

use App\Models\Company;
use App\Models\Plant;
use App\Models\Sloc;
use Livewire\Component;
use Livewire\WithPagination;

class WarehouseList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public $plants = [];

    public $c, $p, $q;
    public function render()
    {
        $companies = Company::all();
        $warehouses = Sloc::with(['company', 'plant'])
            ->when($this->c, fn ($query, $c) => $query->where('company_id', $c))
            ->when($this->p, fn ($query, $p) => $query->where('plant_id', $p))
            ->when($this->q, fn ($query, $q) => $query->where(fn ($query) =>
            $query->where('sloc_code', 'like', '%' . $q . '%')
                ->orWhere('sloc_name', 'like', '%' . $q . '%')))
            ->latest()
            ->paginate(10);
        return view('livewire.warehouse.warehouse-list', compact('companies', 'warehouses'));
    }

    public function search()
    {
        $this->resetPage();
    }

    public function updatedC()
    {
        $this->plants = Plant::where('company_id', $this->c)
            ->get();
    }

    public function delete($id)
    {
        try {
            $sloc = Sloc::find($id);
            if ($sloc->hasDataById() || $sloc->hasDataByCode()) {
                throw new \Exception("Warehouse has data. Can't be deleted");
            }
            $sloc->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
