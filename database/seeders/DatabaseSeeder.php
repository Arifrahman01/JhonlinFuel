<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Company;
use App\Models\Department;
use App\Models\Equipment;
use App\Models\Fuelman;
use App\Models\Issue\IssueDetail;
use App\Models\Issue\IssueHeader;
use App\Models\Material\Material;
use App\Models\Material\MaterialStock;
use App\Models\Menu;
use App\Models\Period;
use App\Models\Permission;
use App\Models\Plant;
use App\Models\Role;
use App\Models\Sloc;
use App\Models\Uom;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            MenusTableSeeder::class,
            MaterialsTableSeeder::class,
        ]);
    }
}
