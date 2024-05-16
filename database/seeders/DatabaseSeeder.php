<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Material;
use App\Models\Plant;
use App\Models\Sloc;
use App\Models\Uom;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Administrator',
            'username' => 'sa',
            'password' => Hash::make('Jhonlin@123'),
        ]);

        Company::create([
            'company_code' => 'JG-Holding',
            'company_name' => 'JG-Holding',
        ]);

        Plant::create([
            'plant_code' => 'JG-BTL',
            'plant_name' => 'JG Batulicin',
        ]);

        Sloc::create([
            'sloc_code' => 'JG-BTL-1',
            'sloc_name' => 'JG Batulicin 1',
        ]);

        Uom::create([
            'uom_code' => 'L',
            'uom_name' => 'Liter',
        ]);

        Material::create([
            'material_code' => '36000001',
            'part_no' => 'SOLAR',
            'material_mnemonic' => 'PERTAMINA',
            'material_description' => 'SOLAR',
            'uom_id' => 1,
        ]);
    }
}
