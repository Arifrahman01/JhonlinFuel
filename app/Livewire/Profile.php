<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    
    public $name, $username, $email, $company;
    public $oldPassword, $newPassword, $confirmPassword;

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
    }

    public function render()
    {
        $users = User::with(['roles.permissions' => function ($query) {
            $query->orderBy('menu_id', 'asc');
        }])->where('id', auth()->user()->id)->get();
        return view('livewire.profile',compact('users'));

    }

    public function updateProfile(){
        try {
            $this->validate([
                'name' => 'required',
                'username' => 'required',
                'email' => 'required|email',
            ]);
            $user = auth()->user();
            $user->name = $this->name;
            $user->username = $this->username;
            $user->email = $this->email;
            $user->save();
            $this->dispatch('success', 'Profile successfully updated.');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
    
    public function changePassword(){
        try {
            $this->validate([
                'oldPassword' => 'required',
                'newPassword' => 'required|min:6',
                'confirmPassword' => 'required|same:newPassword',
            ]);
            if (!Hash::check($this->oldPassword, auth()->user()->password)){

                $this->dispatch('error', 'Old password is incorrect');

            } else if (strcmp($this->oldPassword, $this->newPassword) == 0) {

                $this->dispatch('error', 'New Password cannot be same as your current password');

            }else if ($this->newPassword != $this->confirmPassword) {
                $this->dispatch('error', 'New Password and Confirm Password does not match');
            }else{
                $user = auth()->user();
                $user->password = Hash::make($this->newPassword);
                $user->save();
                $this->dispatch('successChange', 'Password successfully changed. Please log in with your new password.');
            }
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function closeModal()
    {
        $this->oldPassword = null;
        $this->newPassword = null;
        $this->confirmPassword = null;
    }
    
}
