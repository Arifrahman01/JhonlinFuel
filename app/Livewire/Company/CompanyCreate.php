<?php

namespace App\Livewire\Company;

use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class CompanyCreate extends Component
{
    public $loading = false;
    public $statusModal = 'Create';
    public $companyId;
    public $companyCode;
    public $companyCodeReadOnly = false;
    public $companyName;
    protected $listeners = ['openCreate'];
    public function render()
    {
        $permissions = [
            'view-master-company',
            'create-master-company',
            'edit-master-company',
            'delete-master-company',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('livewire.company.company-create');
    }

    public function closeModal()
    {
        $this->loading = true;
    }

    public function openCreate($id = null)
    {
        $permissions = [
            'create-master-company',
            'edit-master-company',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->loading = true;
        if ($id) {
            $this->statusModal = 'Edit';
            $company = Company::find($id);
            $this->companyCodeReadOnly = $company->hasDataByCode();
            $this->companyId = $id;
            $this->companyCode = $company->company_code;
            $this->companyName = $company->company_name;
        } else {
            $this->statusModal = 'Create';
            $this->companyId = null;
            $this->companyCode = null;
            $this->companyName = null;
        }

        $this->loading = false;
    }

    public function store()
    {
        $permissions = [
            'create-master-company',
            'edit-master-company',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        DB::beginTransaction();
        try {

            if ($this->companyId) {
                $this->validate([
                    'companyCode' => [
                        'required',
                        Rule::unique('companies', 'company_code')->ignore($this->companyId),
                    ],
                    'companyName' => 'required',
                ]);
                $company = Company::find($this->companyId);
                if ($company->hasDataByCode()) {
                    throw new \Exception("Company Code has data. Can't be edited");
                }
                $company->update([
                    'company_code' => $this->companyCode,
                    'company_name' => $this->companyName,
                ]);
            } else {
                $this->validate([
                    'companyCode' => 'required|unique:companies,company_code',
                    'companyName' => 'required',
                ]);
                Company::create([
                    'company_code' => $this->companyCode,
                    'company_name' => $this->companyName,
                ]);
            }
            DB::commit();
            $this->dispatch('success', $this->companyId ? 'Data has been updated' : 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
