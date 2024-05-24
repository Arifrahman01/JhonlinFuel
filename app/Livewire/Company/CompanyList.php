<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Component;

class CompanyList extends Component
{
    public function render()
    {
        $companies = Company::paginate(10);
        return view('livewire.company.company-list', compact('companies'));
    }
}
