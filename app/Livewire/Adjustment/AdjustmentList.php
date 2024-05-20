<?php

namespace App\Livewire\Adjustment;

use App\Models\Adjustment\AdjustmentHeader;
use Livewire\Component;
use Livewire\WithPagination;

class AdjustmentList extends Component
{
    use WithPagination;

    public $adjNo;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public function render()
    {
        $adjusts = AdjustmentHeader::with([
            'details.plant',
            'details.sloc',
            'company',
        ])
            ->search(['adjNo' => $this->adjNo])
            ->paginate(2);
        return view('livewire.adjustment.adjustment-list', compact('adjusts'));
    }

    public function search()
    {
        $this->resetPage();
    }
}
