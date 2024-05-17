<?php

namespace App\Livewire\Quota;

use App\Models\RequestHeader;
use Livewire\Component;

class QuotaList extends Component
{
    public function render()
    {
        $quotas = RequestHeader::with(['company','period','details.uom'])->paginate(10);
        return view('livewire.quota.quota-list',compact('quotas'));
    }

    public function delete($id)
    {
        try {
            RequestHeader::find($id)->details()->delete();
            $header = RequestHeader::findOrFail($id);
            $header->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
}
