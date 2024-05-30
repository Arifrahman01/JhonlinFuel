<?php

namespace Database\Seeders;

use App\Models\Material\Material;
use App\Models\Uom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $uom = Uom::create([
            'uom_code' => 'L',
            'uom_name' => 'Liter',
        ]);

        Material::create([
            'material_code' => '36000001',
            'part_no' => 'SOLAR',
            'material_mnemonic' => 'PERTAMINA',
            'material_description' => 'SOLAR',
            'uom_id' => $uom->id,
        ]);
    }
}
