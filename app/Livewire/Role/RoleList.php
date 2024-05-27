<?php

namespace App\Livewire\Role;

use App\Models\Role;
use Livewire\Component;

class RoleList extends Component
{
    public function render()
    {
        $roles = Role::paginate(10);
        return view('livewire.role.role-list', compact('roles'));
    }
}
