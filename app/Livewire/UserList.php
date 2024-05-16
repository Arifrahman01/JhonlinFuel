<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserList extends Component
{
    public $data;
    public $name;
    public $email;
    public $username;
    public $role;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    use WithPagination;

    public function render()
    {
        $roles = Role::all();
        $users = User::with('role')
            ->when($this->name, function ($query) {
                $query->where('name', 'like', '%' . $this->name . '%');
            })->when($this->email, function ($query) {
                $query->where('email', 'like', '%' . $this->email . '%');
            })->when($this->username, function ($query) {
                $query->where('username', 'like', '%' . $this->username . '%');
            })->when($this->role, function ($query) {
                $query->where('role_id', $this->role);
            })->paginate(10);

        return view('livewire.user.user-list', compact('roles', 'users'));
    }
    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
    public function reset_password($id)
    {
        try {
            $user = User::find($id);
            $user->password = bcrypt('Jhonlin@123');
            $user->save();
            $this->dispatch('success', ' Reset password success');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
