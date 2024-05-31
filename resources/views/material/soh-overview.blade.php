<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        SOH Overview
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
                                        <th>Company/Plant/Sloc</th>
                                        <th style="text-align: right;">SOH</th>
                                        <th style="text-align: right;">Intransit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr role="row" aria-level="1" aria-posinset="1" aria-setsize="1"
                                        aria-expanded="true">
                                        <td role="gridcell">{{ $allJhonlin->company_name }}</td>
                                        <td role="gridcell" style="text-align: right;">
                                            {{ number_format($allJhonlin->oh_qty, 0, ',', '.') }}</td>
                                        <td role="gridcell" style="text-align: right;">
                                            {{ number_format($allJhonlin->intransit_qty, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @foreach ($sohPerCompany as $sohCompany)
                                        <tr role="row" aria-level="2" aria-posinset="2" aria-setsize="3"
                                            aria-expanded="false">
                                            <td role="gridcell">{{ $sohCompany->company_name }}</td>
                                            <td role="gridcell" style="text-align: right;">
                                                {{ number_format($sohCompany->oh_qty, 0, ',', '.') }}</td>
                                            <td role="gridcell" style="text-align: right;">
                                                {{ number_format($sohCompany->intransit_qty, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @foreach ($sohPerPlant as $sohPlant)
                                            @if ($sohPlant->company_id == $sohCompany->company_id)
                                                <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1"
                                                    aria-expanded="false">
                                                    <td role="gridcell">{{ $sohPlant->plant_name }}</td>
                                                    <td role="gridcell" style="text-align: right;">
                                                        {{ number_format($sohPlant->oh_qty, 0, ',', '.') }}
                                                    </td>
                                                    <td role="gridcell" style="text-align: right;">
                                                        {{ number_format($sohPlant->intransit_qty, 0, ',', '.') }}</td>
                                                </tr>
                                                @foreach ($sohPerSloc as $sohSloc)
                                                    @if ($sohSloc->plant_id == $sohPlant->plant_id)
                                                        <tr role="row" aria-level="4" aria-posinset="1"
                                                            aria-setsize="1" class="hidden">
                                                            <td role="gridcell">{{ $sohSloc->sloc_name }}</td>
                                                            <td role="gridcell" style="text-align: right;">
                                                                {{ number_format($sohSloc->oh_qty, 0, ',', '.') }}</td>
                                                            <td role="gridcell" style="text-align: right;">
                                                                {{ number_format($sohSloc->intransit_qty, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
