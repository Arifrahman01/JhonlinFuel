<?php

namespace App\Livewire\Plant;

use App\Models\Company;
use App\Models\Plant;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class PlantList extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public $c, $q;

    public function render()
    {
        $permissions = [
            'view-master-plant',
            'create-master-plant',
            'edit-master-plant',
            'delete-master-plant',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $companies = Company::allowed('view-master-plant')->get();
        $plants = Plant::when($this->c, fn ($query, $c) => $query->where('company_id', $c))
            ->when($this->q, fn ($query, $q) => $query->where(fn ($query) =>
            $query->where('plant_code', 'like', '%' . $q . '%')
                ->orWhere('plant_name', 'like', '%' . $q . '%')))
            ->latest()
            ->paginate(10);
        return view('livewire.plant.plant-list', compact('companies', 'plants'));
    }

    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $permissions = [
            'delete-master-plant',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        try {
            $plant = Plant::find($id);
            if ($plant->hasDataById() || $plant->hasDataByCode()) {
                throw new \Exception("Plant has data. Can't be deleted");
            }
            $plant->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
