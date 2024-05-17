<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Equipment;
use App\Models\Issue\IssueDetail;
use App\Models\Issue\IssueHeader;
use App\Models\Material\Material;
use App\Models\Period;
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

        $company = Company::create([
            'company_code' => 'JG-Holding',
            'company_name' => 'JG-Holding',
        ]);

        $plant = Plant::create([
            'company_id' => $company->id,
            'plant_code' => 'JG-BTL',
            'plant_name' => 'JG Batulicin',
        ]);

        Sloc::create([
            'company_id' => $company->id,
            'plant_id' => $plant->id,
            'sloc_code' => 'JG-BTL-1',
            'sloc_name' => 'JG Batulicin 1',
        ]);

        $uom = Uom::create([
            'uom_code' => 'L',
            'uom_name' => 'Liter',
        ]);

        $material = Material::create([
            'material_code' => '36000001',
            'part_no' => 'SOLAR',
            'material_mnemonic' => 'PERTAMINA',
            'material_description' => 'SOLAR',
            'uom_id' => $uom->id,
        ]);

        Period::create([
            'period_start' => '2024-05-01',
            'period_end' => '2024-05-31',
        ]);

        $activity = Activity::create([
            'company_id' => $company->id,
            'activity_code' => 'A1',
            'activity_name' => 'Aktivitas 1',
        ]);

        $equipment = Equipment::create([
            'company_id' => $company->id,
            'equipment_no' => 'E1',
            'equipment_description' => 'Equipment 1',
        ]);

        $issueHeader = IssueHeader::create([
            'company_id' => $company->id,
            'issue_no' => 'issue001',
            'department' => 'Department 1',
            'activity_id' => $activity->id,
            'fuelman' => 'BARONG',
            'equipment_id' => $equipment->id,
            'equipment_driver' => 'JUKI',
        ]);

        IssueDetail::create([
            'header_id' => $issueHeader->id,
            'company_id' => $company->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty' => '40',
        ]);
    }
}
