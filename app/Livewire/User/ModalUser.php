<?php

namespace App\Livewire\user;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ModalUser extends Component
{
    public $loading = false;
    public $statusModal = 'Create';

    public $companiesTmp = [];
    public $rolesTmp = [];

    public $userId;
    public $name;
    public $username;
    public $selectedRole;
    public $selectedCompany = [];

    public $roles_ = [];

    protected $listeners = ['openModal'];

    // public function mount()
    // {
    //     $this->loading = true;
    //     if ($this->userId) {
    //         $this->user = User::find($this->userId);
    //         $this->name = $this->user['name'];
    //         $this->username = $this->user['username'];
    //     } else {
    //         $this->user = null;
    //         $this->userId = null;
    //         $this->name = null;
    //         $this->username = null;
    //     }
    // }

    public function openModal($id = null)
    {
        if ($id) {
            $this->statusModal = 'Edit';
            $user = User::with(['roles'])->find($id);
            $this->userId = $id;
            $this->name = $user->name;
            $this->username = $user->username;
            foreach ($user->roles as $role) {
                $this->roles_[] = [
                    'role_value' => $role->id,
                    'role_text' => $role->role_name,
                    'company_value' => $role->pivot->companies,
                    'company_text' => '',
                ];
            }
        } else {
            $this->statusModal = 'Create';
            $this->userId = null;
            $this->name = null;
            $this->username = null;
            $this->roles_ = [];
        }
        $this->selectedRole = null;
        $this->selectedCompany = [];
        $this->loading = false;
    }

    public function closeModal()
    {
        $this->userId = null;
        $this->companiesTmp = [];
        $this->rolesTmp = [];
        $this->name = null;
        $this->username = null;
        $this->selectedRole = null;
        $this->selectedCompany = [];

        $this->roles_ = [];
        $this->loading = true;
    }

    public function render()
    {
        $companies = Company::all();
        $roles = Role::all();

        $this->companiesTmp = $companies->toArray();
        $this->rolesTmp = $roles->toArray();

        return view('livewire.user.modal-user', compact('companies', 'roles'));
    }

    public function addData()
    {
        try {
            throw_if($this->selectedRole == null, new \Exception('Role harus dipilih'));
            throw_if(empty($this->selectedCompany), new \Exception('Company harus dipilih'));
            $companyValues = [];
            $companyTexts = [];
            foreach ($this->selectedCompany as $companyId => $checkList) {
                if ($checkList) {
                    $companyValues[] = $companyId;
                    $companyTexts[] = (collect($this->companiesTmp))->firstWhere('id', $companyId)['company_name'];
                }
            }
            $this->roles_[] = [
                'role_value' => $this->selectedRole,
                'role_text' => collect($this->rolesTmp)->firstWhere('id', $this->selectedRole)['role_name'],
                'company_value' => $companyValues,
                'company_text' => $companyTexts,
            ];
            $this->selectedRole = null;
            $this->selectedCompany = [];
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            // $this->validate([
            //     'name' => 'required|string',
            //     'email' => 'required|email',
            // ]);
            if ($this->userId) {
                $user = User::find($this->userId);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'username' => $this->username,
                    'role_id' => $this->role,
                ]);
                $this->dispatch('success', 'Data has been updated');
            } else {
                $this->validate([
                    'name' => 'required|string',
                    'username' => 'required|string|unique:users',
                    'roles_' => 'required|array',
                ], [
                    'name.required' => 'Name harus diisi',
                    'username.required' => 'Username harus diisi',
                    'roles_.required' => 'Role belum ditambahkan',
                ]);
                $user = User::create([
                    'name' => $this->name,
                    'username' => $this->username,
                    'password' => bcrypt('Jhonlin@123'),
                ]);

                $roleIds = array_map(function ($entry) {
                    return $entry['role_value'];
                }, $this->roles_);

                $user->roles()->sync($roleIds);

                foreach ($user->roles as $role) {
                    foreach ($this->roles_ as $entry) {
                        if ($entry['role_value'] == $role->id) {
                            $role->pivot->companies()->sync($entry['company_value']);
                        }
                    }
                }
                $this->dispatch('success', 'Data has been created');
            }
            DB::commit();
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
