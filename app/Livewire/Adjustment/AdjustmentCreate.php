<?php

namespace App\Livewire\Adjustment;

use App\Models\Material\MaterialStock;
use App\Models\Plant;
use App\Models\Sloc;
use Livewire\Component;

class AdjustmentCreate extends Component
{
    public $plants;
    public $adjustment;
    public $selectedPlant;
    public $slocs = [];
    public $selectedSloc;
    public $soh = '-';
    public $sohAdjustment;
    // public $sohAfter = '-';
    public $isLoadingSloc = false;
    public $loading = false;

    protected $listeners = ['openModal'];

    public function mount()
    {
        $userCompany = 1;
        $this->plants = Plant::where('company_id', $userCompany)->get();
    }

    public function render()
    {
        // $userCompany = 1; // auth()->user()->company_id;
        // $plants = Plant::where('company_id', $userCompany)->get();
        return view('livewire.adjustment.adjustment-create');
    }

    public function openModal()
    {
        $this->loading = true;
        // if ($userId) {
        //     $this->user = User::find($userId);
        //     $this->name = $this->user['name'];
        //     $this->email = $this->user['email'];
        //     $this->username = $this->user['username'];
        //     $this->role = $this->user['role_id'];
        // } else {
        //     $this->user = null;
        //     $this->userId = null;
        //     $this->name = null;
        //     $this->email = null;
        //     $this->username = null;
        //     $this->role = null;
        // }
        $this->loading = false;
    }

    public function updatedSelectedPlant($value)
    {
        $this->dispatch('logData', 'panggil fungsi updatedSelectedPlant');
        $this->dispatch('logData', 'Selected Plant: ' . $value);
        $this->slocs = Sloc::where('plant_id', $value)->get();
    }

    public function updatedSelectedSloc($value)
    {
        $this->dispatch('logData', 'panggil fungsi updatedSelectedSloc');
        $this->dispatch('logData', 'Selected Sloc: ' . $value);

        if (empty($value)) {
            $this->soh = '-';
        } else {
            $materialStock = MaterialStock::where('sloc_id', $value)
                ->where('status', 'on-hand')
                ->first();
            $this->soh = $materialStock->qty;
        }
        // $this->soh = 2000;
    }

    public function getSloc($value)
    {
        $this->dispatch('logData', 'panggil fungsi getSloc');
        // dd('updatedSelectA called with value: ' . $value);
        // Logika untuk memperbarui selectB berdasarkan nilai selectA
        // if ($value == 'option1') {
        //     $this->selectB = [
        //         'option1_1' => 'Option 1.1',
        //         'option1_2' => 'Option 1.2',
        //     ];
        // } elseif ($value == 'option2') {
        //     $this->selectB = [
        //         'option2_1' => 'Option 2.1',
        //         'option2_2' => 'Option 2.2',
        //     ];
        // } else {
        //     $this->selectB = [];
        // }
        // dd($value);
        $this->isLoadingSloc = true;
        $this->slocs = Sloc::where('plant_id', $value)->get();
        $this->selectedSloc = '';
        $this->isLoadingSloc = false;
    }

    public function getSOH()
    {
        $this->dispatch('logData', 'panggil fungsi getSOH');
        $this->dispatch('logData', 'Selected Sloc: ' . $this->selectedSloc);
        // logger('Selected Sloc: ' . $this->selectedSloc);
        // if ($this->selectedSloc == '') {
        //     $this->soh = '-';
        // } else {
        //     $materialStock = MaterialStock::where('sloc_id', $this->selectedSloc)
        //         ->where('status', 'on-hand')
        //         ->first();
        //     $this->soh = $materialStock->qty;
        // }
        // $this->soh = 2000;
    }

    public function adjustSOH()
    {
        // $this->sohAfter = $this->soh + $this->sohAdjustment;
    }

    public function closeModal()
    {
        $this->adjustment = null;
        // $this->userId = null;
        $this->selectedPlant = null;
        $this->slocs = [];
        $this->loading = true;
    }
}
