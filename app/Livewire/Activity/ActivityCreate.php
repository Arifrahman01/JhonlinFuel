<?php

namespace App\Livewire\Activity;

use App\Models\Activity;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Symfony\Component\CssSelector\Node\FunctionNode;
use Symfony\Component\HttpFoundation\Response;

class ActivityCreate extends Component
{
    public $loading = false;
    public $statusModal = 'Create';
    public $company, $activityCode, $activityName, $notes;
    public $activityId;
    public $codeReadOnly = false;
    protected $listeners = ['openCreate'];

    public function mount()
    {
        $this->loading = true;
    }
    public function render()
    {
        $permissions = [
            'view-master-activity',
            'create-master-activity',
            'edit-master-activity',
            'delete-master-activity',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $companies = Company::allowed('view-master-activity')->get();
        return view('livewire.activity.activity-create', ['companies' => $companies]);
    }
    public function closeModal()
    {
        $this->loading = true;
    }
    public function openCreate($id = null)
    {
        $permissions = [
            'create-master-activity',
            'edit-master-activity',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if ($id) {
            $this->statusModal = 'Edit';
            $activity = Activity::find($id);
            $this->codeReadOnly = $activity->hasData();
            $this->company = $activity->company_id;
            $this->activityCode = $activity->activity_code;
            $this->activityName = $activity->activity_name;
            $this->notes = $activity->notes;
            $this->activityId = $id;
        } else {
            $this->activityId = null;
            $this->codeReadOnly = false;
            $this->statusModal = 'Create';
            $this->company = null;
            $this->activityCode = null;
            $this->activityName = null;
            $this->notes = null;
        }
        $this->loading = false;
    }

    public function store()
    {
        $permissions = [
            'create-master-activity',
            'edit-master-activity',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        DB::beginTransaction();
        try {
            if ($this->activityId) {
                $this->validate([
                    'company' => 'required|exists:companies,id',
                    'activityCode' => 'required|string|max:255',
                    'activityName' => 'required|string|max:255',
                    'notes' => 'required|string',
                ]);
                $activity = Activity::find($this->activityId);
                $activity->update([
                    'company_id' => $this->company,
                    'activity_code' => $this->activityCode,
                    'activity_name' => $this->activityName,
                    'notes' => $this->notes,
                ]);
            } else {
                $this->validate([
                    'company' => 'required|exists:companies,id',
                    'activityCode' => [
                        'required',
                        'string',
                        'max:255',
                        Rule::unique('activities', 'activity_code')->whereNull('deleted_at'),
                    ],
                    'activityName' => 'required',
                    'notes' => 'required',
                ]);
                Activity::create([
                    'company_id' => $this->company,
                    'activity_code' => $this->activityCode,
                    'activity_name' => $this->activityName,
                    'notes' => $this->notes,
                ]);
            }
            DB::commit();
            $this->dispatch('success', $this->activityId ? 'Data has been updated' : 'Data has been created');
            $this->closeModal();
            $this->dispatch('closeModal');
            $this->dispatch('refreshPage');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('error', $th->getMessage());
        }
    }
}
