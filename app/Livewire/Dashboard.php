<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\Material\MaterialStock;
use App\Models\Plant;
use App\Models\Sloc;
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
        $maxCapacity = Sloc::sum('capacity');
        $materialStock = $results = MaterialStock::with('company')->selectRaw('SUM(qty_soh) as total_soh, SUM(qty_intransit) as total_intransit, company_id')->groupBy('company_id')->get();
        $totalFuel = MaterialStock::selectRaw("'All Jhonlin' as company_name")->selectRaw("SUM(IFNULL(qty_soh, 0)) as oh_qty")->selectRaw("SUM(IFNULL(qty_intransit, 0)) as intransit_qty")->first();
        $companyStock = Company::with(['plants.slocs','plants.materialStock'])->get();

        return view('livewire.dashboard',compact('maxCapacity','materialStock','totalFuel','companyStock'));
    }
}
