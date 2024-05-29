<?php

namespace App\Livewire\Activity;

use App\Models\Activity;
use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityList extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['refreshPage'];
    public $c, $q;
    
    public function render()
    {
        $companies = Company::all();
        $activitys = Activity::with(['company'])->search($this->q)
        ->latest()
        ->paginate(10);
        return view('livewire.activity.activity-list', ['activitys' => $activitys,'companies' => $companies]);
    }

    public function delete($id)
    {
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
