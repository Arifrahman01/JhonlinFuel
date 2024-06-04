<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
                            <h2>Fuel Consumption</h2>
                        </div>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form wire:submit.prevent="search">
                                <div class="d-flex">
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="date" class="form-control form-control-sm" id="start_date" onchange="setEndDateMax()" wire:model="start_date" aria-label="Start Date"
                                                placeholder="Start Date" value="{{ $start_date }}">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            s/d
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="date" class="form-control form-control-sm" id="end_date" wire:model="end_date" aria-label="End Date" placeholder="End Date"
                                                value="{{ $end_date }}">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fa fa-search"></i> &nbsp; Cari &nbsp;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table table-striped table-bordered">
                                        <thead>
                                            <tr class="text-nowrap">
                                                <th class="text-center" style="width: 5%">#</th>
                                                <th class="text-center">Equipment </th>
                                                <th class="text-center">Desription</th>
                                                <th class="text-center">Total Destination</th>
                                                <th class="text-center">Total Fuel Used</th>
                                                <th class="text-center">Average</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (empty($consumptions))
                                                {!! dataNotFond(8) !!}
                                            @else
                                                @foreach ($consumptions as $index => $consumption)
                                                    <tr class="text-nowrap">
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td class="text-center">{{ $consumption->equipment_no }}</td>
                                                        <td class="text-left">{{ $consumption->equipment_description }}</td>
                                                        <td class="text-end">{{ number_format($consumption->total_distance, '2', ',', '.') }}</td>
                                                        <td class="text-end">{{ number_format($consumption->total_fuel_used, '2', ',', '.') }}</td>
                                                        <td class="text-end">{{ number_format($consumption->avg_km_per_liter, '2', ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer justify-content-between align-items-center">
                                    {{-- {{ $transfers->links() }} --}}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
