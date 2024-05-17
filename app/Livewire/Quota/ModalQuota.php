<?php

namespace App\Livewire\Quota;

use App\Models\Company;
use App\Models\Material;
use App\Models\Period;
use App\Models\RequestHeader;
use Livewire\Component;

class ModalQuota extends Component
{    
    public $loading = false;
    public $data;
    public $id;
    public $company_id, $request_no, $period_id, $material_id, $qty, $notes;

    protected $listeners = ['openModal'];
    public function mount()
    {
        $this->loading = true;
        // if ($this->id) {
        //     $this->data = RequestHeader::find($this->id)->with(['company','period','details.uom'])->first();
        // }else{
        //     $this->data = RequestHeader::with(['company', 'period', 'details.uom'])->first();
        // }
    }
    public function openModal($id=null)
    {
        $this->loading = true;
        if ($id) {
            
        }else{
            $this->id = null;
        }
        
        $this->loading = false;
    }

    public function render()
    {
        $materialModal = Material::all();
        $companyModal = Company::all();
        $periodeModal = Period::all();
        return view('livewire.quota.modal-quota',compact('materialModal','companyModal','periodeModal'));
    }
    public function storeUser($id=null)
    {
        try {
            if ($id) {
                $this->dispatch('success', $id);
            }else{
                $header = RequestHeader::create([
                    'company_id' => $this->company_id,
                    'request_no' => $this->request_no,
                    'period_id' => $this->period_id,
                    'notes' => $this->notes,
                ]);
                $materialByID = Material::find($this->material_id);
                $header->details()->create([
                    'header_id' => $header->id,
                    'company_id'    => $this->company_id,
                    'material_id' => $this->material_id,
                    'material_code' => $materialByID->material_code,
                    'part_no' => $materialByID->part_no,
                    'material_mnemonic' => $materialByID->material_mnemonic,
                    'material_description' => $materialByID->material_description,
                    'uom_id' => $materialByID->uom_id,
                    'qty' => $this->qty,
                ]);
                $this->dispatch('success', 'Data has been created');
            }
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            // dd($th->getMessage());
            $this->dispatch('error', $th->getMessage());
        }

    }


    public function closeModal()
    {
        $this->data = null;
        $this->id = null;
        $this->loading = true;
        $this->company_id = null;
        $this->request_no = null;
        $this->period_id = null;
        $this->material_id = null;
        $this->qty = null;
        $this->notes = null;
    }

}
