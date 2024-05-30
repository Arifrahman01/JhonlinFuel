<?php

namespace App\Livewire\Equipment;

use App\Models\Company;
use App\Models\Equipment;
use App\Models\Plant;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class EquipmentList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $c, $p, $q;
    public $plants = [];

    public function render()
    {
        $permissions = [
            'view-master-equipment',
            'create-master-equipment',
            'edit-master-equipment',
            'delete-master-equipment',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $companies = Company::all();
        $equipments = Equipment::with(['company', 'plant'])
            ->when($this->c, fn ($query, $c) => $query->where('company_id', $c))
            ->when($this->p, fn ($query, $p) => $query->where('plant_id', $p))
            ->when($this->q, fn ($query, $q) => $query->where(fn ($query) =>
            $query->where('equipment_no', 'like', '%' . $q . '%')
                ->orWhere('equipment_description', 'like', '%' . $q . '%')))
            ->latest()
            ->paginate(10);
        return view('livewire.equipment.equipment-list', ['equipments' => $equipments, 'companies' => $companies]);
    }
    public function delete($id)
    {
        $permissions = [
            'delete-master-equipment',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            Equipment::where('id', $id)->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
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
