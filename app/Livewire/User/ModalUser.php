<?php

namespace App\Livewire\user;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class ModalUser extends Component
{
    public $user;
    public $userId;
    public $loading = false;

    public $name, $email, $role, $username;

    protected $listeners = ['openModal'];

    public function mount()
    {
        $this->loading = true;
        if ($this->userId) {
            $this->user = User::find($this->userId);
            $this->name = $this->user['name'];
            $this->email = $this->user['email'];
            $this->username = $this->user['username'];
            $this->role = $this->user['role_id'];
        } else {
            $this->user = null;
            $this->userId = null;
            $this->name = null;
            $this->email = null;
            $this->username = null;
            $this->role = null;
        }
    }
    public function openModal($userId = null)
    {
        $this->loading = true;
        if ($userId) {
            $this->user = User::find($userId);
            $this->name = $this->user['name'];
            $this->email = $this->user['email'];
            $this->username = $this->user['username'];
            $this->role = $this->user['role_id'];
        } else {
            $this->user = null;
            $this->userId = null;
            $this->name = null;
            $this->email = null;
            $this->username = null;
            $this->role = null;
        }
        $this->loading = false;
    }

    public function closeModal()
    {
        $this->user = null;
        $this->userId = null;
        $this->loading = true;
    }

    public function render()
    {
        $rolesModal = Role::all();
        return view('livewire.user.modal-user', compact('rolesModal'));
    }

    public function storeUser($userId=null)
    {
        try {
            $this->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'role' => 'required|exists:roles,id',
            ]);
            if ($userId) {
                $user = User::find($userId);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'username' => $this->username,
                    'role_id' => $this->role,
                ]);
                $this->dispatch('success', 'Data has been updated');
            } else {
                $this->validate([
                    'username' => 'required|string|unique:users',
                ]);
                $user = User::create([  
                    'name' => $this->name,
                    'email' => $this->email,
                    'username' => $this->username,
                    'role_id' => $this->role,
                    'password' => bcrypt('Jhonlin@123'),
                ]);
                $this->dispatch('success', 'Data has been created');
            }

            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
