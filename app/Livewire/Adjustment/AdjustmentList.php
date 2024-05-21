<?php

namespace App\Livewire\Adjustment;

use App\Models\Adjustment\AdjustmentHeader;
use Livewire\Component;
use Livewire\WithPagination;

class AdjustmentList extends Component
{
    use WithPagination;

    public $adjNo;
    public $title;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public function render()
    {
        $title = "HahaHihi";
        $adjusts = AdjustmentHeader::with([
            'details.plant',
            'details.sloc',
            'company',
        ])
            ->search(['adjNo' => $this->adjNo])
            ->latest()
            ->paginate(10);
        return view('livewire.adjustment.adjustment-list', compact('adjusts'));
    }

    public function search()
    {
        $this->resetPage();
    }
}
