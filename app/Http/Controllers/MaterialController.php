<?php

namespace App\Http\Controllers;

use App\Models\Material\MaterialStock;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function sohOverview(Request $request): View
    {
        $allJhonlin = MaterialStock::selectRaw("'All Jhonlin' as company_name")
            ->selectRaw("SUM(CASE WHEN status = 'on-hand' THEN qty ELSE 0 END) as oh_qty")
            ->selectRaw("SUM(CASE WHEN status = 'intransit' THEN qty ELSE 0 END) as intransit_qty")
            ->first();

        $sohPerCompany = MaterialStock::leftJoin('companies as b', 'material_stocks.company_id', '=', 'b.id')
            ->select(
                'material_stocks.company_id',
                'b.company_name',
                DB::raw('SUM(CASE WHEN material_stocks.status = "on-hand" THEN material_stocks.qty ELSE 0 END) as oh_qty'),
                DB::raw('SUM(CASE WHEN material_stocks.status = "intransit" THEN material_stocks.qty ELSE 0 END) as intransit_qty')
            )
            ->groupBy('material_stocks.company_id', 'b.company_name')
            ->get();

        $sohPerPlant = MaterialStock::select([
            'material_stocks.company_id',
            'material_stocks.plant_id',
            'plants.plant_name',
            DB::raw('SUM(CASE WHEN material_stocks.status = "on-hand" THEN material_stocks.qty ELSE 0 END) as oh_qty'),
            DB::raw('SUM(CASE WHEN material_stocks.status = "intransit" THEN material_stocks.qty ELSE 0 END) as intransit_qty')
        ])
            ->leftJoin('plants', 'material_stocks.plant_id', '=', 'plants.id')
            ->groupBy('material_stocks.company_id')
            ->groupBy('material_stocks.plant_id')
            ->groupBy('plants.plant_name')
            ->get();

        $sohPerSloc = MaterialStock::select([
            'material_stocks.company_id',
            'material_stocks.plant_id',
            'material_stocks.sloc_id',
            'storage_locations.sloc_name',
            DB::raw('SUM(CASE WHEN material_stocks.status = "on-hand" THEN material_stocks.qty ELSE 0 END) as oh_qty'),
            DB::raw('SUM(CASE WHEN material_stocks.status = "intransit" THEN material_stocks.qty ELSE 0 END) as intransit_qty')
        ])
            ->leftJoin('storage_locations', 'material_stocks.sloc_id', '=', 'storage_locations.id')
            ->groupBy('material_stocks.company_id')
            ->groupBy('material_stocks.plant_id')
            ->groupBy('material_stocks.sloc_id')
            ->groupBy('storage_locations.sloc_name')
            ->get();

        return view('material.soh-overview', compact('allJhonlin', 'sohPerCompany', 'sohPerPlant', 'sohPerSloc'));
    }
}
