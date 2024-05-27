<?php

namespace App\Livewire\Role;

use App\Models\Company;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RoleCreate extends Component
{
    public $loading;
    public $statusModal = 'Create';
    public $otorisasi = [];
    public $roleCode;
    public $roleName;
    protected $listeners = ['openCreate'];

    public function render()
    {
        $permissions = Permission::with(['menu'])->get();
        return view('livewire.role.role-create', compact('permissions'));
    }

    public function openCreate($id = null)
    {
        if ($id) {
            $this->statusModal = 'Edit';
            // $plant = Plant::find($id);
            // $this->plantCodeReadOnly = $plant->hasDataByCode();
            // $this->selectedCompany = $plant->company_id;
            // $this->plantId = $id;
            // $this->plantCode = $plant->plant_code;
            // $this->plantName = $plant->plant_name;
        } else {
            $this->statusModal = 'Create';
            // $this->selectedCompany = null;
            // $this->plantId = null;
            // $this->plantCode = null;
            // $this->plantName = null;
        }
        $this->loading = false;
    }

    public function closeModal()
    {
        // dd($this->permissions);
        $this->loading = false;
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            Role::create([
                'role_code' => $this->roleCode,
                'role_name' => $this->roleName,
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }

        foreach ($this->otorisasi as $permissionId => $checkList) {
        }
    }
}
