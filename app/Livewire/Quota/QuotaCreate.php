<?php

namespace App\Livewire\Quota;

use App\Models\Company;
use App\Models\Material\Material;
use App\Models\Period;
use App\Models\Quota;
use App\Models\Uom;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class QuotaCreate extends Component
{
    public $loading = true;
    public $id;
    public $selectedMaterial, $periode, $uom;  /* Input Header */
    public $code, $partno, $mnemonic, $description; /* Material */
    public $quotas = []; /* Input Details */
    public $companies;
    public $readOnly = false;

    protected $listeners = ['openCreate'];

    public function mount()
    {
        $this->companies = Company::all();
        foreach ($this->companies as $company) {
            $this->quotas[$company->id] = '';
        }
    }

    public function openCreate($period = null)
    {
        if ($period) {
            $quotas = Quota::where('period_id', $period)->get();
            if (!$quotas->isEmpty()) {
                foreach ($quotas as $key => $value) {
                    $this->quotas[$value->company_id] = (int)$value->qty;
                }
                $this->selectedMaterial = $quotas[0]->material_id;
                $this->periode = $quotas[0]->period_id;
                $this->uom = $quotas[0]->uom_id;
                $material = Material::find($this->selectedMaterial);
                $this->code = $material->material_code;
                $this->partno = $material->part_no;
                $this->mnemonic = $material->material_mnemonic;
                $this->description = $material->material_description;
                $this->readOnly = true;
            }

        } else {
            $this->closeModal();
        }
        $this->loading = false;
    }
    public function updateQuota($companyId, $quota)
    {
        $this->quotas[$companyId] = $quota;
    }

    public function updatedSelectedMaterial($value)
    {
        if ($value) {
            $material = Material::find($value);
            $this->code = $material->material_code;
            $this->partno = $material->part_no;
            $this->mnemonic = $material->material_mnemonic;
            $this->description = $material->material_description;
        } else {
            $this->reset(['code', 'partno', 'mnemonic','description']);
        }
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            // $cekPeriod = Quota::where('period_id', $this->periode)->first();
            // if ($cekPeriod) {
            //     throw new \Exception("Quota periode " . Period::find($this->periode)->value('period_name') . ' sudah di daftarkan');
            // }
            foreach ($this->quotas as $companyId => $quota) {
                $param = [
                    'company_id'    => $companyId,
                    'period_id'     => $this->periode,
                    'qty'           => ($quota == '') ? 0  : $quota,
                    'material_id'   => $this->selectedMaterial,
                    'material_code' => $this->code,
                    'part_no'       => $this->partno,
                    'material_mnemonic' => $this->mnemonic,
                    'material_description' => $this->description,
                    'uom_id'        => $this->uom,
                ];
                Quota::updateOrCreate(
                    ['company_id' => $companyId, 'period_id' => $this->periode],
                    $param
                );
            }
            $this->dispatch('success', 'Data berhasil disimpan');
            $this->closeModal();
            DB::commit();
        } catch (\Throwable $th) {
            // dd($th);
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function render()
    {
        $periods = Period::all();
        $materials = Material::all();
        $companies = Company::allowed('view-dashboard')->get();
        $uoms = Uom::all();
        return view('livewire.quota.quota-create', compact('periods', 'materials', 'companies', 'uoms'));
    }

    public function closeModal()
    {
        $this->dispatch('closeModal');
        $this->loading = true;
        $this->id = null;
        $this->reset(['selectedMaterial', 'code', 'partno', 'mnemonic', 'quotas', 'periode', 'uom']);
        foreach ($this->companies as $company) {
            $this->quotas[$company->id] = '';
        }
        $this->readOnly = false;
        $this->dispatch('refreshPage');
    }
}
