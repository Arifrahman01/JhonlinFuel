<?php

namespace App\Livewire\Period;

use App\Models\Period;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Str;

class PeriodList extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];

    public $periodCompanies = [];

    public $periodId, $periodName, $startDate, $endDate;

    public $q;


    public function mount()
    {
        $period = Period::latest()->first();
        $this->periodCompanies = data_get($period, 'companies');
    }

    public function render()
    {
        // $permissions = [
        //     'view-master-period',
        //     'create-master-period',
        //     'edit-master-period',
        //     'delete-master-period',
        // ];
        // abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $periods = Period::with(['companies'])
            ->latest()
            ->paginate(10);

        $periodQuery = Period::with(['companies']);
        if ($this->periodId) {
            $periodQuery->where('id', $this->periodId);
        } else {
            $periodQuery->latest();
        }
        $period = $periodQuery->first();
        $this->periodName = $period->period_name;
        $this->startDate = $period->start_date;
        $this->endDate = $period->end_date;
        $this->periodCompanies = data_get($period, 'companies');
        return view('livewire.period.period-list', [
            'periods' => $periods,
        ]);
    }

    public function search()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $permissions = [
            'delete-master-period',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $period = Period::find($id);
            if ($period->hasDataById()) {
                throw new \Exception("period has data. Can't be deleted");
            }
            $period->delete();
            $this->periodId = null;
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }

    public function periodSelected($periodId)
    {
        $this->periodId = $periodId;
    }

    public function openPeriod($periodId, $companyId)
    {
        $period = Period::find($periodId);

        if ($period) {
            $period->companies()->updateExistingPivot($companyId, ['status' => 'open']);
        }
        $this->dispatch('success', 'Data has been updated');
        $this->resetPage();
    }

    public function closePeriod($periodId, $companyId)
    {
        $period = Period::find($periodId);

        if ($period) {
            $period->companies()->updateExistingPivot($companyId, ['status' => 'close']);
        }

        $this->dispatch('success', 'Data has been updated');
        $this->resetPage();
    }
}
