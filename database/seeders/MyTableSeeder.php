<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Fuelman;
use App\Models\Material\Material;
use App\Models\Material\MaterialStock;
use App\Models\Menu;
use App\Models\Permission;
use App\Models\Plant;
use App\Models\Role;
use App\Models\Sloc;
use App\Models\Uom;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // User::create([
        //     'name' => 'Super Administrator',
        //     'username' => 'sa',
        //     'password' => Hash::make('Jhonlin@123'),
        // ]);

        $jgh = Company::create([
            'company_code' => 'JG-Holding',
            'company_name' => 'JG-Holding',
        ]);

        $jg = Company::create([
            'company_code' => 'JG',
            'company_name' => 'PT. Jhonlin Group',
        ]);

        $jb = Company::create([
            'company_code' => 'JB',
            'company_name' => 'PT. Jhonlin Baratama',
        ]);

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

        $kam = Company::create([
            'company_code' => 'KAM',
            'company_name' => 'PT. Kodeco Agrojaya Mandiri',
        ]);

        $jmt = Company::create([
            'company_code' => 'JMT',
            'company_name' => 'PT. Jhonlin Marine Trans',
        ]);

        $seiDuaPlant = Plant::create([
            'company_id' => $jgh->id,
            'plant_code' => 'sei-dua',
            'plant_name' => 'Sungai Dua',
        ]);

        $bunatiPlant = Plant::create([
            'company_id' => $jgh->id,
            'plant_code' => 'bunati',
            'plant_name' => 'Bunati',
        ]);

        $tanjungPlant = Plant::create([
            'company_id' => $jgh->id,
            'plant_code' => 'tanjung',
            'plant_name' => 'Tanjung',
        ]);

        $jgBtlPlant = Plant::create([
            'company_id' => $jg->id,
            'plant_code' => 'JG-BTL',
            'plant_name' => 'JG Batulicin',
        ]);

        $jbSei2Plant = Plant::create([
            'company_id' => $jb->id,
            'plant_code' => 'jb-sei-2',
            'plant_name' => 'JB Sungai Dua',
        ]);

        $jbBunatiPlant = Plant::create([
            'company_id' => $jb->id,
            'plant_code' => 'jb-bunati',
            'plant_name' => 'JB Bunati',
        ]);

        $jbTanjungPlant = Plant::create([
            'company_id' => $jb->id,
            'plant_code' => 'jb-tanjung',
            'plant_name' => 'JB Tanjung',
        ]);

        $hoKamPlant = Plant::create([
            'company_id' => $kam->id,
            'plant_code' => 'ho-kam',
            'plant_name' => 'HO KAM',
        ]);

        $dnbePlant = Plant::create([
            'company_id' => $kam->id,
            'plant_code' => 'dnbe',
            'plant_name' => 'Danau Biru Estate',
        ]);

        $dnbmPlant = Plant::create([
            'company_id' => $kam->id,
            'plant_code' => 'dnbm',
            'plant_name' => 'Danau Biru Mill',
        ]);

        $kbtmPlant = Plant::create([
            'company_id' => $kam->id,
            'plant_code' => 'kbtm',
            'plant_name' => 'Karang Bintang Mill',
        ]);

        $bktePlant = Plant::create([
            'company_id' => $kam->id,
            'plant_code' => 'bkte',
            'plant_name' => 'Bukit Taliud Estate',
        ]);

        $bktmPlant = Plant::create([
            'company_id' => $kam->id,
            'plant_code' => 'bktm',
            'plant_name' => 'Bukit Taliud Mill',
        ]);

        $jmtKdcPlant = Plant::create([
            'company_id' => $jmt->id,
            'plant_code' => 'jmt-kdc',
            'plant_name' => 'JMT Kodeco',
        ]);


        $tanki1sloc = Sloc::create([
            'company_id' => $jgh->id,
            'plant_id' => $seiDuaPlant->id,
            'sloc_code' => 'tanki-1',
            'sloc_name' => 'Tanki 1 Sei Dua',
        ]);

        $tanki2sloc = Sloc::create([
            'company_id' => $jgh->id,
            'plant_id' => $seiDuaPlant->id,
            'sloc_code' => 'tanki-2',
            'sloc_name' => 'Tanki 2 Sei Dua',
        ]);

        $tanki1Bntsloc = Sloc::create([
            'company_id' => $jgh->id,
            'plant_id' => $bunatiPlant->id,
            'sloc_code' => 'tanki-1-bnt',
            'sloc_name' => 'Tanki 1 Bunati',
        ]);

        $tanki1Tjgsloc = Sloc::create([
            'company_id' => $jgh->id,
            'plant_id' => $tanjungPlant->id,
            'sloc_code' => 'tanki-1-tjg',
            'sloc_name' => 'Tanki 1 Tanjung',
        ]);

        $tankiPal1Sloc = Sloc::create([
            'company_id' => $jg->id,
            'plant_id' => $jgBtlPlant->id,
            'sloc_code' => 'tanki-pal1',
            'sloc_name' => 'Tanki Pal 1',
        ]);

        $tankiSei2Sloc = Sloc::create([
            'company_id' => $jb->id,
            'plant_id' => $jbSei2Plant->id,
            'sloc_code' => 'tanki-sei-2',
            'sloc_name' => 'Tanki Sungai 2',
        ]);

        $fth001Sloc = Sloc::create([
            'company_id' => $jb->id,
            'plant_id' => $jbSei2Plant->id,
            'sloc_code' => 'fth001',
            'sloc_name' => 'FTH001',
        ]);

        $fth002Sloc = Sloc::create([
            'company_id' => $jb->id,
            'plant_id' => $jbSei2Plant->id,
            'sloc_code' => 'fth002',
            'sloc_name' => 'FTH002',
        ]);

        $tankiBntSloc = Sloc::create([
            'company_id' => $jb->id,
            'plant_id' => $jbBunatiPlant->id,
            'sloc_code' => 'tanki-bnt',
            'sloc_name' => 'Tanki Bunati',
        ]);

        $fth011Sloc = Sloc::create([
            'company_id' => $jb->id,
            'plant_id' => $jbBunatiPlant->id,
            'sloc_code' => 'fth011',
            'sloc_name' => 'FTH011',
        ]);

        $fth012Sloc = Sloc::create([
            'company_id' => $jb->id,
            'plant_id' => $jbBunatiPlant->id,
            'sloc_code' => 'fth012',
            'sloc_name' => 'FTH012',
        ]);

        $tankiTjgSloc = Sloc::create([
            'company_id' => $jb->id,
            'plant_id' => $jbTanjungPlant->id,
            'sloc_code' => 'tanki-tjg',
            'sloc_name' => 'Tanki Tanjung',
        ]);

        $fth009Sloc = Sloc::create([
            'company_id' => $jb->id,
            'plant_id' => $jbTanjungPlant->id,
            'sloc_code' => 'fth009',
            'sloc_name' => 'FTH009',
        ]);

        $tankiKrjSloc = Sloc::create([
            'company_id' => $kam->id,
            'plant_id' => $hoKamPlant->id,
            'sloc_code' => 'tanki-krj',
            'sloc_name' => 'Tanki Kuranji',
        ]);

        $tankiDnbeSloc = Sloc::create([
            'company_id' => $kam->id,
            'plant_id' => $dnbePlant->id,
            'sloc_code' => 'tanki-dnbe',
            'sloc_name' => 'Tanki DNBE',
        ]);

        $tankiDnbmSloc = Sloc::create([
            'company_id' => $kam->id,
            'plant_id' => $dnbmPlant->id,
            'sloc_code' => 'tanki-dnbm',
            'sloc_name' => 'Tanki DNBM',
        ]);

        $tankiKbtmSloc = Sloc::create([
            'company_id' => $kam->id,
            'plant_id' => $kbtmPlant->id,
            'sloc_code' => 'tanki-kbtm',
            'sloc_name' => 'Tanki KBTM',
        ]);

        $tankiBkteSloc = Sloc::create([
            'company_id' => $kam->id,
            'plant_id' => $bktePlant->id,
            'sloc_code' => 'tanki-bkte',
            'sloc_name' => 'Tanki BKTE',
        ]);

        $tankiBktmSloc = Sloc::create([
            'company_id' => $kam->id,
            'plant_id' => $bktmPlant->id,
            'sloc_code' => 'tanki-bktm',
            'sloc_name' => 'Tanki BKTM',
        ]);

        $tankiJmtKdcSloc = Sloc::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_code' => 'tanki-jmt-kdc',
            'sloc_name' => 'Tanki Kodeco',
        ]);

        $tankiTb001Sloc = Sloc::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_code' => 'tb001',
            'sloc_name' => 'TB001',
        ]);

        $tankiTb002Sloc = Sloc::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_code' => 'tb002',
            'sloc_name' => 'TB002',
        ]);

        $tankiTb003Sloc = Sloc::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_code' => 'tb003',
            'sloc_name' => 'TB003',
        ]);

        $tankiTb004Sloc = Sloc::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_code' => 'tb004',
            'sloc_name' => 'TB004',
        ]);

        $tankiTb005Sloc = Sloc::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_code' => 'tb005',
            'sloc_name' => 'TB005',
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

        // Period::create([
        //     'period_start' => '2024-05-01',
        //     'period_end' => '2024-05-31',
        // ]);

        $activity = Activity::create([
            'company_id' => $jg->id,
            'activity_code' => 'A1',
            'activity_name' => 'Aktivitas 1',
        ]);

        $equipment = Equipment::create([
            'company_id' => $jg->id,
            'plant_id' => $jgBtlPlant->id,
            'equipment_no' => 'E1',
            'equipment_description' => 'Equipment 1',
        ]);

        MaterialStock::create([
            'company_id' => $jgh->id,
            'plant_id' => $seiDuaPlant->id,
            'sloc_id' => $tanki1sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 10000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jgh->id,
            'plant_id' => $seiDuaPlant->id,
            'sloc_id' => $tanki2sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 8000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jgh->id,
            'plant_id' => $bunatiPlant->id,
            'sloc_id' => $tanki1Bntsloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 15000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jgh->id,
            'plant_id' => $tanjungPlant->id,
            'sloc_id' => $tanki1Tjgsloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 15000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jg->id,
            'plant_id' => $jgBtlPlant->id,
            'sloc_id' => $tankiPal1Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 17000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jg->id,
            'plant_id' => $jgBtlPlant->id,
            'sloc_id' => $tankiPal1Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 5000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jb->id,
            'plant_id' => $jbSei2Plant->id,
            'sloc_id' => $tankiSei2Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 13000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jb->id,
            'plant_id' => $jbSei2Plant->id,
            'sloc_id' => $fth001Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 3000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jb->id,
            'plant_id' => $jbSei2Plant->id,
            'sloc_id' => $fth002Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 4000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jb->id,
            'plant_id' => $jbBunatiPlant->id,
            'sloc_id' => $tanki1Bntsloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 12000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jb->id,
            'plant_id' => $jbBunatiPlant->id,
            'sloc_id' => $fth011Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 5000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jb->id,
            'plant_id' => $jbBunatiPlant->id,
            'sloc_id' => $fth012Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 3500,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jb->id,
            'plant_id' => $jbTanjungPlant->id,
            'sloc_id' => $tankiTjgSloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 50000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jb->id,
            'plant_id' => $jbTanjungPlant->id,
            'sloc_id' => $fth009Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 10000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $kam->id,
            'plant_id' => $hoKamPlant->id,
            'sloc_id' => $tankiKrjSloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 5000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $kam->id,
            'plant_id' => $dnbePlant->id,
            'sloc_id' => $tankiDnbeSloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 10000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $kam->id,
            'plant_id' => $dnbmPlant->id,
            'sloc_id' => $tankiDnbmSloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 7000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $kam->id,
            'plant_id' => $kbtmPlant->id,
            'sloc_id' => $tankiKbtmSloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 7000,
            'qty_intransit' => -2000,
        ]);

        MaterialStock::create([
            'company_id' => $kam->id,
            'plant_id' => $bktePlant->id,
            'sloc_id' => $tankiBkteSloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 5000,
            'qty_intransit' => 2000,
        ]);

        MaterialStock::create([
            'company_id' => $kam->id,
            'plant_id' => $bktmPlant->id,
            'sloc_id' => $tankiBktmSloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 9000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_id' => $tankiTb001Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 25000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_id' => $tankiTb002Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 25000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_id' => $tankiTb003Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 25000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_id' => $tankiTb004Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 25000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jmt->id,
            'plant_id' => $jmtKdcPlant->id,
            'sloc_id' => $tankiTb005Sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 25000,
            'qty_intransit' => 0,
        ]);

        MaterialStock::create([
            'company_id' => $jgh->id,
            'plant_id' => $seiDuaPlant->id,
            'sloc_id' => $tanki1sloc->id,
            'material_id' => $material->id,
            'material_code' => $material->material_code,
            'part_no' => $material->part_no,
            'material_mnemonic' => $material->material_mnemonic,
            'material_description' => $material->material_description,
            'uom_id' => $uom->id,
            'qty_soh' => 2000,
            'qty_intransit' => 0,
        ]);

        Fuelman::create([
            'company_id' => $jg->id,
            'plant_id' => $jgBtlPlant->id,
            'nik' => '12311072',
            'name' => 'SABRIADI',
        ]);

        Department::create([
            'company_id' => $jg->id,
            'plant_id' => $jgBtlPlant->id,
            'department_code' => 'HRD',
            'department_name' => 'Human Resource Department',
        ]);

        $menus = [
            'Dashboard' => [
                'View Dashboard',
            ],
            'Transaksi Receipt PO' => [
                'View Transaksi Receipt PO',
            ],
            'Transaksi Transfer' => [
                'View Transaksi Transfer',
            ],
            'Transaksi Receipt Transfer' => [
                'View Transaksi Receipt Transfer',
            ],
            'Transaksi Issue' => [
                'View Transaksi Issue',
            ],
            'Transaksi Adjustment' => [
                'View Transaksi Adjustment',
                'Create Transaksi Adjustment',
            ],
            'Loader Receipt PO' => [
                'View Loader Receipt PO',
                'Create Loader Receipt PO',
                'Edit Loader Receipt PO',
                'Delete Loader Receipt PO',
                'Posting Loader Receipt PO',
            ],
            'Loader Transfer' => [
                'View Loader Transfer',
                'Create Loader Transfer',
                'Edit Loader Transfer',
                'Delete Loader Transfer',
                'Posting Loader Transfer',
            ],
            'Loader Receipt Transfer' => [
                'View Loader Receipt Transfer',
                'Create Loader Receipt Transfer',
                'Edit Loader Receipt Transfer',
                'Delete Loader Receipt Transfer',
                'Posting Loader Receipt Transfer',
            ],
            'Loader Issue' => [
                'View Loader Issue',
                'Create Loader Issue',
                'Edit Loader Issue',
                'Delete Loader Issue',
                'Posting Loader Issue',
            ],
            'Master Company' => [
                'View Master Company',
                'Create Master Company',
                'Edit Master Company',
                'Delete Master Company',
                'Posting Master Company',
            ],
            'Master Plant' => [
                'View Master Plant',
                'Create Master Plant',
                'Edit Master Plant',
                'Delete Master Plant',
                'Posting Master Plant',
            ],
            'Master Warehouse' => [
                'View Master Warehouse',
                'Create Master Warehouse',
                'Edit Master Warehouse',
                'Delete Master Warehouse',
                'Posting Master Warehouse',
            ],
            'Master Fuelman' => [
                'View Master Fuelman',
                'Create Master Fuelman',
                'Edit Master Fuelman',
                'Delete Master Fuelman',
                'Posting Master Fuelman',
            ],
            'Master Department' => [
                'View Master Department',
                'Create Master Department',
                'Edit Master Department',
                'Delete Master Department',
                'Posting Master Department',
            ],
            'Master Activity' => [
                'View Master Activity',
                'Create Master Activity',
                'Edit Master Activity',
                'Delete Master Activity',
                'Posting Master Activity',
            ],
            'Master Equipment' => [
                'View Master Equipment',
                'Create Master Equipment',
                'Edit Master Equipment',
                'Delete Master Equipment',
                'Posting Master Equipment',
            ],
            'Master Material' => [
                'View Master Material',
                'Create Master Material',
                'Edit Master Material',
                'Delete Master Material',
                'Posting Master Material',
            ],
            'Master UOM' => [
                'View Master UOM',
                'Create Master UOM',
                'Edit Master UOM',
                'Delete Master UOM',
                'Posting Master UOM',
            ],
            'Report SOH Overview' => [
                'View Report SOH Overview',
            ],
            'User' => [
                'View User',
                'Create User',
                'Edit User',
                'Delete User',
            ],
            'Role' => [
                'View Role',
                'Create Role',
                'Edit Role',
                'Delete Role',
                'Posting Role',
            ],
        ];

        foreach ($menus as $menu => $permissions) {
            $menu_ = Menu::create([
                'menu_name' => $menu,
            ]);

            foreach ($permissions as $permission) {
                Permission::create([
                    'menu_id' => $menu_->id,
                    'permission_code' => Str::slug($permission),
                    'permission_name' => $permission,
                ]);
            }
        }

        // $issueHeader = IssueHeader::create([
        //     'company_id' => $company->id,
        //     'issue_no' => 'issue001',
        //     'department' => 'Department 1',
        //     'activity_id' => $activity->id,
        //     'fuelman' => 'BARONG',
        //     'equipment_id' => $equipment->id,
        //     'equipment_driver' => 'JUKI',
        // ]);

        // IssueDetail::create([
        //     'header_id' => $issueHeader->id,
        //     'company_id' => $company->id,
        //     'material_id' => $material->id,
        //     'material_code' => $material->material_code,
        //     'part_no' => $material->part_no,
        //     'material_mnemonic' => $material->material_mnemonic,
        //     'material_description' => $material->material_description,
        //     'uom_id' => $uom->id,
        //     'qty' => '40',
        // ]);

    }
}
