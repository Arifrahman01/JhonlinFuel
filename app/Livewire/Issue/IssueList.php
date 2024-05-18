<?php

namespace App\Livewire\Issue;

use App\Models\Issue\IssueHeader;
use Livewire\Component;

class IssueList extends Component
{
    public $issue;
    public $equipment;
    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        // $users = User::with('role')
        //     ->when($this->name, function ($query) {
        //         $query->where('name', 'like', '%' . $this->name . '%');
        //     })->when($this->email, function ($query) {
        //         $query->where('email', 'like', '%' . $this->email . '%');
        //     })->when($this->username, function ($query) {
        //         $query->where('username', 'like', '%' . $this->username . '%');
        //     })->when($this->role, function ($query) {
        //         $query->where('role_id', $this->role);
        //     })->paginate(10);

        $issues = IssueHeader::with('details')
            ->when($this->issue, function ($query) {
                $query->where('issue_no', 'like', '%' . $this->issue . '%');
            })
            ->paginate(10);
        return view('livewire.issue.issue-list', compact('issues'));
    }
}
