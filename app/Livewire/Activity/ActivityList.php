<?php

namespace App\Livewire\Activity;

use App\Models\Activity;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class ActivityList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $c, $q;

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
        $activitys = Activity::with(['company'])->search($this->q)
            ->latest()
            ->paginate(10);
        return view('livewire.activity.activity-list', ['activitys' => $activitys, 'companies' => $companies]);
    }

    public function delete($id)
    {
        $permissions = [
            'delete-master-activity',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            Activity::where('id', $id)->delete();
            $this->dispatch('success', 'Data has been deleted');
        } catch (\Throwable $th) {
            $this->dispatch('error', $th->getMessage());
        }
    }
    public function search()
    {
        $this->resetPage();
    }
}
