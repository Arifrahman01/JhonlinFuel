<?php

namespace App\Livewire\user;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

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
    public $allCompany;

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
                    'company_value' => data_get($role, 'pivot.companies.*.id'),
                    'company_text' => data_get($role, 'pivot.companies.*.company_name'),
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
        $permissions = [
            'view-user',
            'create-user',
            'edit-user',
            'delete-user',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
            // Periksa apakah role_value sudah ada
            if (!$this->roleExists($this->roles_, $this->selectedRole)) {
                // Tambahkan ke array jika role_value belum ada
                $this->roles_[] = [
                    'role_value' => $this->selectedRole,
                    'role_text' => collect($this->rolesTmp)->firstWhere('id', $this->selectedRole)['role_name'],
                    'company_value' => $companyValues,
                    'company_text' => $companyTexts,
                ];
            }
            $this->selectedRole = null;
            $this->selectedCompany = [];
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    private function roleExists($roles, $selectedRole)
    {
        foreach ($roles as $role) {
            if ($role['role_value'] === $selectedRole) {
                return true;
            }
        }
        return false;
    }

    // public function updatedAllCompany($value)
    // {
    //     $this->selectedCompany = [];
    //     if ($value) {
    //         foreach ($this->companiesTmp as $record) {
    //             $this->selectedCompany[$record['id']] = true;
    //         }
    //     }
    // }

    // public function updatedSelectedCompany()
    // {
    //     $this->allCompany = count($this->selectedCompany) == count($this->companiesTmp);
    // }

    public function store()
    {
        $permissions = [
            'create-user',
            'edit-user',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::beginTransaction();
        try {

            if ($this->userId) {
                $this->validate([
                    'name' => 'required|string',
                    'username' => [
                        'required',
                        Rule::unique('users')
                            ->ignore($this->userId)
                            ->whereNull('deleted_at'),
                    ],
                    'roles_' => 'required|array',
                ], [
                    'name.required' => 'Name harus diisi',
                    'username.required' => 'Username harus diisi',
                    'username.unique' => 'Username tidak boleh sama',
                    'roles_.required' => 'Role belum ditambahkan',
                ]);
                $user = User::find($this->userId);
                $user->update([
                    'name' => $this->name,
                    'username' => $this->username,
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
                $this->dispatch('success', 'Data has been updated');
            } else {
                $this->validate([
                    'name' => 'required|string',
                    'username' => [
                        'required',
                        Rule::unique('users')
                            ->whereNull('deleted_at'),
                    ],
                    'roles_' => 'required|array',
                ], [
                    'name.required' => 'Name harus diisi',
                    'username.required' => 'Username harus diisi',
                    'username.unique' => 'Username tidak boleh sama',
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

    public function editItem($index)
    {
        $this->selectedRole = $this->roles_[$index]['role_value'];
        foreach ($this->roles_[$index]['company_value'] as $value) {
            $this->selectedCompany[$value] = true;
        }
        // $this->selectedCompany = data_get($this->roles_[$index], 'company_value');
        // $this->roles_[] = [
        //     'role_value' => $role->id,
        //     'role_text' => $role->role_name,
        //     'company_value' => data_get($role, 'pivot.companies.*.id'),
        //     'company_text' => data_get($role, 'pivot.companies.*.company_name'),
        // ];
        unset($this->roles_[$index]);
        $this->roles_ = array_values($this->roles_);
    }

    public function deleteItem($index)
    {
        unset($this->roles_[$index]);
        $this->roles_ = array_values($this->roles_);
    }
}
