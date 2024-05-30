<?php

namespace App\Livewire\Department;

use App\Models\Company;
use App\Models\Department;
use App\Models\Plant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class DepartmentCreate extends Component
{
    public $loading = false;
    public $statusModal = 'Create';
    public $selectedCompany;
    public $departmentId;
    public $departmentCode;
    public $departmentCodeReadOnly = false;
    public $departmentName;
    protected $listeners = ['openCreate'];
    public function render()
    {
        $permissions = [
            'view-master-department',
            'create-master-department',
            'edit-master-department',
            'delete-master-department',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $companies = Company::all();
        return view('livewire.department.department-create', compact('companies'));
    }

    public function closeModal()
    {
        $this->loading = true;
    }

    public function openCreate($id = null)
    {
        $permissions = [
            'create-master-department',
            'edit-master-department',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->loading = true;
        if ($id) {
            $this->statusModal = 'Edit';
            $department = Department::find($id);
            $this->departmentCodeReadOnly = $department->hasDataByCode();
            $this->selectedCompany = $department->company_id;
            $this->departmentId = $id;
            $this->departmentCode = $department->department_code;
            $this->departmentName = $department->department_name;
        } else {
            $this->statusModal = 'Create';
            $this->selectedCompany = null;
            $this->departmentId = null;
            $this->departmentCode = null;
            $this->departmentName = null;
        }

        $this->loading = false;
    }

    public function store()
    {
        $permissions = [
            'create-master-department',
            'edit-master-department',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();
        try {

            if ($this->departmentId) {
                $this->validate([
                    'selectedCompany' => 'required',
                    'departmentCode' => [
                        'required',
                        Rule::unique('departments', 'department_code')->ignore($this->departmentId),
                    ],
                    'departmentName' => 'required',
                ]);
                $department = Department::find($this->departmentId);
                if ($department->hasDataByCode()) {
                    throw new \Exception("Department Code has data. Can't be edited");
                }
                $department->update([
                    'company_id' => $this->selectedCompany,
                    'department_code' => $this->departmentCode,
                    'department_name' => $this->departmentName,
                ]);
            } else {
                $this->validate([
                    'selectedCompany' => 'required',
                    'departmentCode' => 'required|unique:departments,department_code',
                    'departmentName' => 'required',
                ]);
                $department = Department::create([
                    'company_id' => $this->selectedCompany,
                    'department_code' => $this->departmentCode,
                    'department_name' => $this->departmentName,
                ]);
            }
            DB::commit();
            $this->dispatch('success', $this->departmentId ? 'Data has been updated' : 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
