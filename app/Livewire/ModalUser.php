<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class ModalUser extends Component
{
    public $user;
    public $userId;
    public $loading = false;

    public $name;
    public $email;
    public $role;

    protected $listeners = ['openModal'];

    public function mount()
    {
        $this->loading = true;
        if ($this->userId) {
            $this->user = User::find($this->userId);
            // dd($this->user);
            $this->name = $this->user['name'];
            $this->email = $this->user['email'];
            $this->role = $this->user['role_id'];
        } else {
        }
    }
    public function openModal($userId)
    {
        $this->loading = true;
        $this->user = User::find($userId);

        $this->name = $this->user['name'];
        $this->email = $this->user['email'];
        $this->role = $this->user['role_id'];

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

    public function updateUser($userId)
    {
        try {
            $this->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'role' => 'required|exists:roles,id',
            ]);
            $user = User::find($userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'role_id' => $this->role,
            ]);
            $this->dispatch('success','Data has been updated');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
