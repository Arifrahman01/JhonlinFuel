<?php

namespace App\Livewire\Company;

use Livewire\Component;

class CompanyCreate extends Component
{
    public $loading = false;
    public function render()
    {
        return view('livewire.company.company-create');
    }

    public function closeModal()
    {
        $this->loading = false;
    }
}
