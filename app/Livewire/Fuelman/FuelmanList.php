<?php

namespace App\Livewire\Fuelman;

use App\Models\Company;
use App\Models\Fuelman;
use App\Models\Plant;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class FuelmanList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public $plants = [];

    public $c, $p, $q;
    public function render()
    {
        $permissions = [
            'view-master-fuelman',
            'create-master-fuelman',
            'edit-master-fuelman',
            'delete-master-fuelman',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
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

    public function delete($id)
    {
        $permissions = [
            'delete-master-fuelman',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $fuelman = Fuelman::find($id);
            if ($fuelman->hasDataById() || $fuelman->hasDataByNik()) {
                throw new \Exception("Fuelman has data. Can't be deleted");
            }
            $fuelman->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
