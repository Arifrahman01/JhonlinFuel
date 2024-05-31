<?php

namespace App\Livewire\User;


use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class UserList extends Component
{

    public $q;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    use WithPagination;

    public function render()
    {
        $permissions = [
            'view-user',
            'create-user',
            'edit-user',
            'delete-user',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::all();
        $users = User::with('roles')
            ->search([
                'q' => $this->q,
            ])->paginate(10);

        return view('livewire.user.user-list', compact('roles', 'users'));
    }
    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $permissions = [
            'delete-user',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
