<?php

namespace App\Livewire\Quota;

use App\Models\Request\RequestHeader as RequestRequestHeader;
use App\Models\RequestHeader;
use Livewire\Component;

class QuotaList extends Component
{
    protected $listeners = ['refreshPage'];
    
    public function render()
    {
        $quotas = RequestRequestHeader::with(['company','period','details.uom'])->paginate(10);
        return view('livewire.quota.quota-list',compact('quotas'));
    }

    public function delete($id)
    {
        try {
            RequestRequestHeader::find($id)->details()->delete();
            $header = RequestRequestHeader::findOrFail($id);
            $header->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
