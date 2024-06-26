<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Stock Overview
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-8">
                    {{-- <div class="card"> --}}
                    <div class="card">
                        <div class="card-header">
                            <form wire:submit.prevent="resetPage">
                                <div class="d-flex">
                                    <div class="ms-auto">
                                        <div class="ms-2 d-inline-block">
                                            <select wire:model='periodId' class="form-select form-select-sm">
                                                <option value="">Current Stock</option>
                                                @foreach ($periods as $period)
                                                    <option value="{{ $period->id }}">
                                                        {{ 'Period ' . $period->period_name }}
                                                    </option>
                                                @endforeach
                                            </select>
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
                        <div class="table-wrap">
                            <table id="treegrid" role="treegrid" aria-label="Inbox"
                                class="table table-sm table-striped">
                                <colgroup>
                                    <col id="treegrid-col1">
                                    <col id="treegrid-col2">
                                    <col id="treegrid-col3">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>Company/Plant/Warehouse</th>
                                        <th style="text-align: right;">SOH</th>
                                        <th style="text-align: right;">Intransit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr role="row" aria-level="1" aria-posinset="1" aria-setsize="1"
                                        aria-expanded="true">
                                        <td role="gridcell">{{ $data->name }}</td>
                                        <td role="gridcell" style="text-align: right;">
                                            {{ number_format($data->soh, 0, ',', '.') }}</td>
                                        <td role="gridcell" style="text-align: right;">
                                            {{ number_format($data->intransit, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @foreach ($data->details as $company)
                                        <tr role="row" aria-level="2" aria-posinset="2" aria-setsize="3"
                                            aria-expanded="false">
                                            <td role="gridcell">{{ $company->name }}</td>
                                            <td role="gridcell" style="text-align: right;">
                                                {{ number_format($company->soh, 0, ',', '.') }}</td>
                                            <td role="gridcell" style="text-align: right;">
                                                {{ number_format($company->intransit, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @foreach ($company->details as $plant)
                                            <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1"
                                                aria-expanded="false" class="hidden">
                                                <td role="gridcell">{{ $plant->name }}</td>
                                                <td role="gridcell" style="text-align: right;">
                                                    {{ number_format($plant->soh, 0, ',', '.') }}
                                                </td>
                                                <td role="gridcell" style="text-align: right;">
                                                    {{ number_format($plant->intransit, 0, ',', '.') }}</td>
                                            </tr>
                                            @foreach ($plant->details as $sloc)
                                                <tr role="row" aria-level="4" aria-posinset="1" aria-setsize="1"
                                                    class="hidden">
                                                    <td role="gridcell">{{ $sloc->name }}</td>
                                                    <td role="gridcell" style="text-align: right;">
                                                        {{ number_format($sloc->soh, 0, ',', '.') }}</td>
                                                    <td role="gridcell" style="text-align: right;">
                                                        {{ number_format($sloc->intransit, 0, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                    {{-- <tr role="row" aria-level="1" aria-posinset="1" aria-setsize="1"
                                        aria-expanded="true">
                                        <td role="gridcell">PT. Jhonlin Group</td>
                                        <td role="gridcell"><span class="badge bg-primary">100%</span></td>
                                        <td role="gridcell">2.123.00</td>
                                    </tr>

                                    <tr role="row" aria-level="2" aria-posinset="2" aria-setsize="3"
                                        aria-expanded="false">
                                        <td role="gridcell">PT. Jhonlin Batubara</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">1.234.456</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1" class="hidden">
                                        <td role="gridcell">Gudang 1</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">500.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1" class="hidden">
                                        <td role="gridcell">Gudang A</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">500.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1" class="hidden">
                                        <td role="gridcell">Gudang 2</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">234.456</td>
                                    </tr>


                                    <tr role="row" aria-level="2" aria-posinset="3" aria-setsize="3"
                                        aria-expanded="false">
                                        <td role="gridcell">PT. Jhonlin Marine Trans</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">1.000.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1"
                                        aria-expanded="false" class="hidden">
                                        <td role="gridcell">Gudang C</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">300.000</td>
                                    </tr>
                                    <tr role="row" aria-level="4" aria-posinset="1" aria-setsize="2" class="hidden">
                                        <td role="gridcell">Kapal A</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">150.000</td>
                                    </tr>
                                    <tr role="row" aria-level="4" aria-posinset="2" aria-setsize="2"
                                        class="hidden">
                                        <td role="gridcell">Kapal 2</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">150.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1"
                                        aria-expanded="false" class="hidden">
                                        <td role="gridcell">Gudang 2</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">300.000</td>
                                    </tr>
                                    <tr role="row" aria-level="4" aria-posinset="1" aria-setsize="2"
                                        class="hidden">
                                        <td role="gridcell">Kapal 3</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">150.000</td>
                                    </tr>
                                    <tr role="row" aria-level="4" aria-posinset="2" aria-setsize="2"
                                        class="hidden">
                                        <td role="gridcell">Kapal 4</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">150.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1"
                                        class="hidden">
                                        <td role="gridcell">Gudang 3</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">300.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1"
                                        class="hidden">
                                        <td role="gridcell">Gudang 3AA</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">300.000</td>
                                    </tr> --}}
                                    {{-- </tr> --}}
                                    {{-- <tr role="row" aria-level="2" aria-posinset="3" aria-setsize="3"
                                        aria-expanded="false">
                                        <td role="gridcell">PT. Jhonlin Marine</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">1.000.000</td>
                                    </tr> --}}
                                    {{-- <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1"
                                        class="hidden">
                                        <td role="gridcell">Gudang 3AA</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">300.000</td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>

                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('./dist/js/custom.js') }}"></script>
    @endpush
    @section('title')
        SOH Overview
    @endsection
</div>
