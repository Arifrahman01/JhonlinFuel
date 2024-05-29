<?php

namespace App\Livewire\Role;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RoleCreate extends Component
{
    public $loading;
    public $statusModal = 'Create';
    public $otorisasi = [];
    public $roleId;
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
            $role = Role::with(['permissions'])->find($id);
            $this->roleId = $id;
            $this->roleCode = $role->role_code;
            $this->roleName = $role->role_name;
            $this->otorisasi = [];
            foreach ($role->permissions as $permission) {
                $this->otorisasi[$permission->id] = true;
            }
        } else {
            $this->statusModal = 'Create';
            $this->roleId = null;
            $this->roleCode = null;
            $this->roleName = null;
            $this->otorisasi = [];
        }
        $this->loading = false;
    }

    public function closeModal()
    {
        // dd($this->permissions);
        $this->loading = true;
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            if ($this->roleId) {
                $this->validate([
                    'roleCode' => [
                        'required',
                        Rule::unique('roles', 'role_code')
                            ->ignore($this->roleId)
                            ->whereNull('deleted_at'),
                    ],
                    'roleName' => 'required',
                ]);
                if (!in_array(true, $this->otorisasi, true)) {
                    throw new \Exception('Otorisasi harus dipilih');
                }
                $role = Role::find($this->roleId);
                $role->update([
                    'role_code' => $this->roleCode,
                    'role_name' => $this->roleName,
                ]);
                $role->permissions()->detach();
                foreach ($this->otorisasi as $permissionId => $checkList) {
                    if ($checkList) {
                        $role->permissions()->attach($permissionId);
                    }
                }
            } else {
                $this->validate([
                    'roleCode' => [
                        'required',
                        Rule::unique('roles', 'role_code')
                            ->whereNull('deleted_at'),
                    ],
                    'roleName' => 'required',
                ]);
                if (!in_array(true, $this->otorisasi, true)) {
                    throw new \Exception('Otorisasi harus dipilih');
                }
                $role = Role::create([
                    'role_code' => $this->roleCode,
                    'role_name' => $this->roleName,
                ]);
                foreach ($this->otorisasi as $permissionId => $checkList) {
                    if ($checkList) {
                        $role->permissions()->attach($permissionId);
                    }
                }
            }

            DB::commit();
            $this->dispatch('success', $this->roleId ? 'Data has been updated' : 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
