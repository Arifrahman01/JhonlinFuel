<?php

namespace App\Livewire\Issue;

use App\Models\Company;
use Livewire\Component;

class ModalIssue extends Component
{
    public $issue;
    public $loading = false;

    protected $listeners = ['openModal'];

    public function render()
    {
        $companies = Company::all();
        return view('livewire.issue.modal-issue', compact('companies'));
    }

    public function openModal($userId = null)
    {
        $this->loading = true;
        // if ($userId) {
        //     $this->user = User::find($userId);
        //     $this->name = $this->user['name'];
        //     $this->email = $this->user['email'];
        //     $this->username = $this->user['username'];
        //     $this->role = $this->user['role_id'];
        // } else {
        //     $this->user = null;
        //     $this->userId = null;
        //     $this->name = null;
        //     $this->email = null;
        //     $this->username = null;
        //     $this->role = null;
        // }
        $this->loading = false;
    }

    public function closeModal()
    {
        $this->issue = null;
        // $this->userId = null;
        $this->loading = true;
    }

    public function storeIssue($issueId = null)
    {
        // try {
        //     $this->validate([
        //         'name' => 'required|string',
        //         'email' => 'required|email',
        //         'role' => 'required|exists:roles,id',
        //     ]);
        //     if ($userId) {
        //         $user = User::find($userId);
        //         $user->update([
        //             'name' => $this->name,
        //             'email' => $this->email,
        //             'username' => $this->username,
        //             'role_id' => $this->role,
        //         ]);
        //         $this->dispatch('success', 'Data has been updated');
        //     } else {
        //         $this->validate([
        //             'username' => 'required|string|unique:users',
        //         ]);
        //         $user = User::create([  
        //             'name' => $this->name,
        //             'email' => $this->email,
        //             'username' => $this->username,
        //             'role_id' => $this->role,
        //             'password' => bcrypt('Jhonlin@123'),
        //         ]);
        //         $this->dispatch('success', 'Data has been created');
        //     }

        //     $this->closeModal();
        //     $this->dispatch('closeModal');
        //     $this->dispatch('refreshPage');
        // } catch (\Throwable $th) {
        //     $this->dispatch('error', $th->getMessage());
        // }
    }
}
