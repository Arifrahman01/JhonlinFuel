<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class UserList extends Component
{
    public $data;
    public $name;
    public $email;
    public $role;

    public function mount()
    {
        $this->data = User::all();
    }
    public function render()
    {
        return view('livewire.user.user-list');
    }

    public function searching()
    {
        $query = User::query();
        if ($this->name) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if ($this->email) {
            $query->where('email', 'like', '%' . $this->email . '%');
        }
        if ($this->role) {
            $query->where('role', $this->role);
        }
        $this->data = $query->get();
    }
}
