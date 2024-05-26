<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public $q;
    public function render()
    {
        $companies = Company::when($this->q, function ($query, $q) {
            $query->where('company_code', 'like', '%' . $q . '%')
                ->orWhere('company_name', 'like', '%' . $q . '%');
        })
            ->latest()
            ->paginate(10);
        return view('livewire.company.company-list', compact('companies'));
    }

    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        try {
            $company = Company::find($id);
            if ($company->hasDataById() || $company->hasDataByCode()) {
                throw new \Exception("Company has data. Can't be deleted");
            }
            $company->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
