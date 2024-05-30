<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class CompanyList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public $q;
    public function render()
    {
        $permissions = [
            'view-master-company',
            'create-master-company',
            'edit-master-company',
            'delete-master-company',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
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
        $permissions = [
            'delete-master-company',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
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
