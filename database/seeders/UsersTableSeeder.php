<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $role = Role::create([
            'role_code' => 'sa',
            'role_name' => 'Super Administrator',
            'notes' => 'JANGAN DIEDIT MAUPUN DIHAPUS, INI ROLE PALING SAKTI'
        ]);

        $user = User::create([
            'name' => 'Super Administrator',
            'username' => 'sa',
            'password' => Hash::make('Jhonlin@123'),
        ]);

        $user->roles()->attach($role->id);
    }
}
