<?php

namespace App\Livewire\user;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class ModalUser extends Component
{
    public $user;
    public $userId;
    public $loading = false;

    public $companiesTmp = [];
    public $rolesTmp = [];
    public $name, $username, $selectedRole;
    public $selectedCompany = [];
    public $datas = [];

    public $roles_ = [
        // [
        //     'role' => 'Admin',
        //     'company' => [
        //         'PT. Jhonlin Group',
        //         'PT. Jhonlin Agromandiri',
        //         'PT. Jhonlin Baratama'
        //     ],
        // ],
        // [
        //     'role' => 'Entry',
        //     'company' => [
        //         'PT. Jhonlin Group',
        //         'PT. Jhonlin Agromandiri'
        //     ],
        // ],
    ];

    protected $listeners = ['openModal'];
    public function mount()
    {
        $this->loading = true;
        if ($this->userId) {
            $this->user = User::find($this->userId);
            $this->name = $this->user['name'];
            $this->username = $this->user['username'];
        } else {
            $this->user = null;
            $this->userId = null;
            $this->name = null;
            $this->username = null;
        }
    }
    public function openModal($userId = null)
    {
        $this->loading = true;
        if ($userId) {
            $this->user = User::find($userId);
            $this->name = $this->user['name'];
            $this->username = $this->user['username'];
        } else {
            $this->user = null;
            $this->userId = null;
            $this->name = null;
            $this->username = null;
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
        $companies = Company::all();
        $roles = Role::all();

        $companiesTmp = $companies->toArray();
        $rolesTmp = $roles->toArray();
        dd($companiesTmp);

        return view('livewire.user.modal-user', compact('companies', 'roles'));
    }

    public function addData()
    {
        $companies = [];
        foreach ($this->selectedCompany as $company => $checkList) {
            if ($checkList) {
                $companies[] = $company;
            }
        }
        $sRole = explode('-', $this->selectedRole)[1];
        $this->roles_[] = [
            'role' => $sRole,
            'company' => array_values($companies),
        ];
        // dd($this->roles_);
    }

    public function storeUser($userId = null)
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
