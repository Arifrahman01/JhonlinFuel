<?php

namespace App\Livewire\Quota;

use App\Models\Company;
use App\Models\Period;
use App\Models\Quota;
use Livewire\Component;
use Livewire\WithPagination;

class QuotaList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $selectedPeriod;

    public function mount()
    {
        $latestPeriod = Period::latest('id')->first();
        $this->selectedPeriod = $latestPeriod->id ?? '';
    }

    public function render()
    {
        $quotas = Quota::with(['company'])->where('period_id',$this->selectedPeriod)->get();
        $periods = Period::all();

        return view('livewire.quota.quota-list', compact('quotas', 'periods'));
    }
}
