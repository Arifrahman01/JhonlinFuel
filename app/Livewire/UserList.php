<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class UserList extends Component
{
    public $data;
    public $name;
    public $email;
    public $role;

    /* Class Animation */
    public $loadingUserId = null;

    public function mount()
    {
        $this->data = User::with('role')->get();
    }

    public function render()
    {
        $roles = Role::all();
        return view('livewire.user.user-list', compact('roles'));
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
            $query->where('role_id', $this->role);
        }
        $this->data = $query->get();
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            $this->data = User::with('role')->get(); 
            $this->dispatch('success', ['message' => 'Data has been deleted']);
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function reset_password($userId)
    {
        $this->loadingUserId = $userId;
        $user = User::find($userId);
        if ($user) {
            $user->password = bcrypt('Jhonlin@123');
            $user->save();
            session()->flash('success', 'Password has been reset successfully!');
        } else {
            session()->flash('error', 'User not found!');
        }
        $this->loadingUserId = null;
    }
}
