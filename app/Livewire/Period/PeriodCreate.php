<?php

namespace App\Livewire\Period;

use App\Models\Company;
use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class PeriodCreate extends Component
{
    public $loading = true;
    public $statusModal;
    public $periodId;
    public $periodName;
    public $startDate;
    public $endDate;
    protected $listeners = ['openCreate'];
    public function render()
    {
        return view('livewire.period.period-create');
    }

    public function openCreate($id = null)
    {
        $permissions = [
            'create-master-period',
            'edit-master-period',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($id) {
            $this->statusModal = 'Edit';
            $period = Period::find($id);
            $this->periodId = $id;
            $this->periodName = $period->period_name;
            $this->startDate = $period->start_date;
            $this->endDate = $period->end_date;
        } else {
            $this->statusModal = 'Create';
            $this->periodId = null;
            $this->periodName = null;
            $this->startDate = null;
            $this->endDate = null;
        }
        $this->loading = false;
    }

    public function store()
    {
        DB::beginTransaction();
        try {
            $period = Period::create([
                'period_name' => $this->periodName,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
            ]);

            $period->companies()->sync(Company::get(['id']));

            DB::commit();
            $this->dispatch('success', $this->periodId ? 'Data has been updated' : 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', $e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->loading = true;
    }
}
