<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'company_code' => 'JG',
            'company_name' => 'PT. Jhonlin Group',
        ]);

        Company::create([
            'company_code' => 'JB',
            'company_name' => 'PT. Jhonlin Baratama',
        ]);

        Company::create([
            'company_code' => 'JMT',
            'company_name' => 'PT. Jhonlin Marine Trans',
        ]);
    }
}
