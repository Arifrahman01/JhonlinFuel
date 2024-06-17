<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            ],
            'Master Plant' => [
                'View Master Plant',
                'Create Master Plant',
                'Edit Master Plant',
                'Delete Master Plant',
            ],
            'Master Warehouse' => [
                'View Master Warehouse',
                'Create Master Warehouse',
                'Edit Master Warehouse',
                'Delete Master Warehouse',
            ],
            'Master Fuelman' => [
                'View Master Fuelman',
                'Create Master Fuelman',
                'Edit Master Fuelman',
                'Delete Master Fuelman',
            ],
            'Master Department' => [
                'View Master Department',
                'Create Master Department',
                'Edit Master Department',
                'Delete Master Department',
            ],
            'Master Activity' => [
                'View Master Activity',
                'Create Master Activity',
                'Edit Master Activity',
                'Delete Master Activity',
            ],
            'Master Equipment' => [
                'View Master Equipment',
                'Create Master Equipment',
                'Edit Master Equipment',
                'Delete Master Equipment',
            ],
            'Master Material' => [
                'View Master Material',
                'Create Master Material',
                'Edit Master Material',
                'Delete Master Material',
            ],
            'Master UOM' => [
                'View Master UOM',
                'Create Master UOM',
                'Edit Master UOM',
                'Delete Master UOM',
            ],
            'Master Period' => [
                'View Master Period',
                'Create Master Period',
                'Edit Master Period',
                'Delete Master Period',
            ],
            'Report Stock Overview' => [
                'View Report Stock Overview',
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
            ],
            'Report Fuel Consumption' => [
                'View Report Fuel Consumption',
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
    }
}
