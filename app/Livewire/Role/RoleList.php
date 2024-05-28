<?php

namespace App\Livewire\Role;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class RoleList extends Component
{
    use WithPagination;
    protected $listeners = ['refreshPage'];
    public function render()
    {
        $roles = Role::with(['permissions'])
            ->latest()
            ->paginate(10);
        // dd($roles);
        return view('livewire.role.role-list', compact('roles'));
    }

    public function delete($id)
    {
        try {
            $role = Role::find($id);
            if ($role->hasDataById()) {
                throw new \Exception("Role has data. Can't be deleted");
            }
            $role->permissions()->detach();
            $role->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
