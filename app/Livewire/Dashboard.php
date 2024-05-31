<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Symfony\Component\HttpFoundation\Response;

class Dashboard extends Component
{
    public function render()
    {

        $permissions = [
            'view-dashboard',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('livewire.dashboard');
    }
}
