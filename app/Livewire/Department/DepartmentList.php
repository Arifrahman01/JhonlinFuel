<?php

namespace App\Livewire\Department;

use App\Models\Company;
use App\Models\Department;
use App\Models\Plant;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $plants = [];
    public $c, $p, $q;
    public function render()
    {
        $companies = Company::all();
        $departments = Department::with(['company', 'plant'])
            ->when($this->c, fn ($query, $c) => $query->where('company_id', $c))
            ->when($this->p, fn ($query, $p) => $query->where('plant_id', $p))
            ->when($this->q, fn ($query, $q) => $query->where(fn ($query) =>
            $query->where('department_code', 'like', '%' . $q . '%')
                ->orWhere('department_name', 'like', '%' . $q . '%')))
            ->latest()
            ->paginate(10);
        return view('livewire.department.department-list', compact('companies', 'departments'));
    }

    public function search()
    {
        $this->resetPage();
    }

    public function updatedC($value)
    {
        $this->plants = Plant::where('company_id', $value)->get();
    }

    public function delete($id)
    {
        try {
            $department = Department::find($id);
            if ($department->hasDataById() || $department->hasDataByCode()) {
                throw new \Exception("Department has data. Can't be deleted");
            }
            $department->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
