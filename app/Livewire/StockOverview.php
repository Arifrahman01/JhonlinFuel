<?php

namespace App\Livewire;

use App\Models\Company;
use App\Models\Material\MaterialStock;
use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\Response;

class StockOverview extends Component
{
    use WithPagination;
    public $title = 'Stock Overview';
    public $periodId;
    // public function render()
    // {
    //     $permissions = [
    //         'view-report-stock-overview',
    //     ];
    //     abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     dd($this->getData());

    //     $periods = Period::all();

    //     $allJhonlin = MaterialStock::selectRaw("'All Jhonlin' as company_name")
    //         ->selectRaw("SUM(IFNULL(qty_soh, 0)) as oh_qty")
    //         ->selectRaw("SUM(IFNULL(qty_intransit, 0)) as intransit_qty")
    //         ->whereNull("deleted_at")
    //         ->first();

    //     $sohPerCompany = MaterialStock::leftJoin('companies as b', 'material_stocks.company_id', '=', 'b.id')
    //         ->select(
    //             'material_stocks.company_id',
    //             'b.company_name',
    //             DB::raw('SUM(IFNULL(material_stocks.qty_soh, 0)) as oh_qty'),
    //             DB::raw('SUM(IFNULL(material_stocks.qty_intransit, 0)) as intransit_qty')
    //         )
    //         ->whereNull('material_stocks.deleted_at')
    //         ->groupBy('material_stocks.company_id', 'b.company_name')
    //         ->get();

    //     $sohPerPlant = MaterialStock::select([
    //         'material_stocks.company_id',
    //         'material_stocks.plant_id',
    //         'plants.plant_name',
    //         DB::raw('SUM(IFNULL(material_stocks.qty_soh, 0)) as oh_qty'),
    //         DB::raw('SUM(IFNULL(material_stocks.qty_intransit, 0)) as intransit_qty')
    //     ])
    //         ->join('plants', function ($join) {
    //             $join->on('material_stocks.plant_id', '=', 'plants.id')
    //                 ->whereNull('plants.deleted_at');
    //         })
    //         ->whereNull('material_stocks.deleted_at')
    //         ->groupBy('material_stocks.company_id')
    //         ->groupBy('material_stocks.plant_id')
    //         ->groupBy('plants.plant_name')
    //         ->get();

    //     $sohPerSloc = MaterialStock::select([
    //         'material_stocks.company_id',
    //         'material_stocks.plant_id',
    //         'material_stocks.sloc_id',
    //         'storage_locations.sloc_name',
    //         DB::raw('SUM(IFNULL(material_stocks.qty_soh, 0)) as oh_qty'),
    //         DB::raw('SUM(IFNULL(material_stocks.qty_intransit, 0)) as intransit_qty')
    //     ])
    //         ->join('storage_locations', function ($join) {
    //             $join->on('material_stocks.sloc_id', '=', 'storage_locations.id')
    //                 ->whereNull('storage_locations.deleted_at');
    //         })
    //         ->whereNull('material_stocks.deleted_at')
    //         ->groupBy('material_stocks.company_id')
    //         ->groupBy('material_stocks.plant_id')
    //         ->groupBy('material_stocks.sloc_id')
    //         ->groupBy('storage_locations.sloc_name')
    //         ->orderBy('material_stocks.id')
    //         ->get();
    //     return view('livewire.stock-overview', compact('periods', 'allJhonlin', 'sohPerCompany', 'sohPerPlant', 'sohPerSloc'));
    // }

    public function render()
    {
        $permissions = [
            'view-report-stock-overview',
        ];
        abort_if(Gate::none($permissions), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $periods = Period::all();

        return view('livewire.stock-overview', [
            'periods' => $periods,
            'data' => $this->getData(),
        ]);
    }

    private function getData()
    {
        $companiesQuery = Company::query();
        if ($this->periodId) {
            $companiesQuery->with(['plants.slocs.periodStock' => function ($query) {
                $query->where('trans_type', 'close')
                    ->where('period_id', $this->periodId);
            }]);
        } else {
            $companiesQuery->with(['plants.slocs.currentStock']);
        }
        $companies = $companiesQuery->get();

        $data = [
            "name" => "All Jhonlin",
            "soh" => 0,
            "intransit" => 0,
            "details" => $companies->map(function ($company) {
                $companySoh = 0;
                $companyIntransit = 0;

                $companyDetails = $company->plants->map(function ($plant) use (&$companySoh, &$companyIntransit) {
                    $plantSoh = 0;
                    $plantIntransit = 0;

                    $plantDetails = $plant->slocs->map(function ($sloc) use (&$plantSoh, &$plantIntransit) {
                        $stock = $this->periodId ? $sloc->periodStock : $sloc->currentStock;
                        $soh = $stock ? $stock->qty_soh : 0;
                        $intransit = $stock ? $stock->qty_intransit : 0;

                        $plantSoh += $soh;
                        $plantIntransit += $intransit;

                        return (object)[
                            "name" => $sloc->sloc_name,
                            "soh" => $soh,
                            "intransit" => $intransit,
                            "details" => []
                        ];
                    })->toArray();

                    $companySoh += $plantSoh;
                    $companyIntransit += $plantIntransit;

                    return (object)[
                        "name" => $plant->plant_name,
                        "soh" => $plantSoh,
                        "intransit" => $plantIntransit,
                        "details" => $plantDetails
                    ];
                })->toArray();

                return (object)[
                    "name" => $company->company_name,
                    "soh" => $companySoh,
                    "intransit" => $companyIntransit,
                    "details" => $companyDetails
                ];
            })->toArray()
        ];

        foreach ($data['details'] as $company) {
            $data['soh'] += $company->soh;
            $data['intransit'] += $company->intransit;
        }

        return (object)$data;
    }
}
