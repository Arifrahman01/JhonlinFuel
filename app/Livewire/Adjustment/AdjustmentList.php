<?php

namespace App\Livewire\Adjustment;

use App\Models\Adjustment\AdjustmentHeader;
use Livewire\Component;

class AdjustmentList extends Component
{
    public function render()
    {
        $adjusts = AdjustmentHeader::with('company')
            ->paginate(10);
        return view('livewire.adjustment.adjustment-list', compact('adjusts'));
    }
}
