<div>
    <style>
        body {
            font-family: sans-serif;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .fu-progress {
            position: relative;
            width: 250px;
            height: 250px;
            border: 5px solid rgb(23, 139, 202);
            border-radius: 50% !important;

            .fu-inner {
                position: absolute;
                overflow: hidden;

                z-index: 2;

                width: 240px;
                height: 240px;
                border: 5px solid #ffffff;
                border-radius: 50% !important;

                .water {
                    position: absolute;
                    z-index: 1;
                    background: rgba(23, 139, 202, 0.5);
                    width: 200%;
                    height: 200%;

                    transform: translateZ(0);
                    -webkit-transform: translateZ(0);

                    transition: all 1s ease !important;
                    -webkit-transition: all 1s ease !important;

                    top: 0%;
                    left: -50%;
                    border: 1px solid transparent;
                    border-radius: 40% !important;

                    animation-duration: 10s;
                    animation-name: spin;
                    animation-iteration-count: infinite;
                    animation-timing-function: linear;
                }

                .glare {
                    position: absolute;
                    top: -120%;
                    left: -120%;
                    z-index: 5;
                    width: 200%;
                    height: 200%;
                    transform: rotate(45deg);
                    background: #ffffff;
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 50%;
                }

                .fu-percent {
                    position: absolute;
                    top: 57px;
                    width: 100%;
                    height: 100%;

                    font-size: 6em;
                    font-weight: bold;
                    color: rgb(4, 86, 129);
                    text-align: center;
                }
            }
        }
    </style>
    @php
        $sisa = $maxCapacity - $totalFuel->oh_qty;
        if ($maxCapacity > 0) {
            $sisaPersen = ($sisa / $maxCapacity) * 100;
            $persentaseTerisi = ($totalFuel->oh_qty / $maxCapacity) * 100;
            $persentaseTerisi = number_format((float) $persentaseTerisi, 2, '.', '');
            if (strpos($persentaseTerisi, '.00') !== false) {
                $persentaseTerisi = number_format((float) $persentaseTerisi);
            }
        } else {
            $sisaPersen = 0;
            $persentaseTerisi = 0;
        }
    @endphp
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Dashboard
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Capacty</div>
                            </div>
                            <br>
                            <div class="h1 mb-2">{{ number_format($maxCapacity, '0', ',', '.') }} Liter</div>
                            <div class="d-flex">
                                <div>Maximum Capacity</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Percentage Filled </div>
                            </div>
                            <br>
                            <div class="h1 mb-2">{{ $persentaseTerisi }} %</div>
                            <div class="d-flex">
                                <div>Stock On Hand</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Stock</div>
                            </div>
                            <br>
                            <div class="h1 mb-2">{{ number_format($totalFuel->oh_qty, '0', ',', '.') }} Liter</div>
                            <div class="d-flex">
                                <div>Stock On Hand</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="subheader">Stock</div>
                            </div>
                            <br>
                            <div class="h1 mb-2">{{ number_format($totalFuel->intransit_qty, '0', ',', '.') }} Liter</div>
                            <div class="d-flex">
                                <div>Intransit</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row row-deck row-cards mt-3 mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="fu-progress text-center">
                                        <div class="fu-inner">
                                            <div class="fu-percent percent">
                                                <h1>{{ number_format($totalFuel->oh_qty, '0', ',', '.') }}</h1>
                                                <h1>Liter</h1>
                                            </div>

                                            <div class="water" style="top: {{ (int) $sisaPersen }}%"></div>
                                            <div class="glare"></div>
                                        </div>
                                    </div>
                                    <h5 style="margin-left: 90px; margin-top:20px">Main Tank</h5>
                                </div>
                                <div class="col-9">
                                    <div class="card" style="height: 18rem">
                                        <div class="card-table table-responsive">
                                            <table class="table table-vcenter" style="font-size: 12px !important;">
                                                <thead style="position: sticky; top: 0px; z-index: 10;">
                                                    <th>Unit</th>
                                                    <th class="text-end">SOH</th>
                                                    <th class="text-end">Intransit</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($materialStock as $material)
                                                        <tr class="text-nowrap">
                                                            <td class="text-secondary">{{ $material->company->company_name }}</td>
                                                            <td class="text-end">{{ number_format($material->total_soh, '0', ',', '.') }}</td>
                                                            <td class="text-end">{{ number_format($material->total_intransit, '0', ',', '.') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--  --}}
            <div class="row row-deck">
                @foreach ($companyStock as $company)
                    <div class="col-6 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h4> {{ $company->company_name }} </h4>
                            </div>
                            @php
                                $maxCapacityUnit = 0;
                                $sohUnit = 0;
                                foreach ($company->slocs as $key => $sloc) {
                                    $maxCapacityUnit += (int) $sloc->capacity;
                                }
                                foreach ($company->plants as $key => $plant) {
                                    foreach ($plant->materialStock as $value) {
                                        $sohUnit += $value->qty_soh;
                                    }
                                }

                                $sisaUnit = $maxCapacityUnit - $sohUnit;
                                if ($sisaUnit != 0 && $maxCapacityUnit != 0) {
                                    $sisaPersenUnit = (($sisaUnit ?? 1) / ($maxCapacityUnit ?? 1)) * 100;
                                }else{
                                    $sisaPersenUnit = 0;
                                }
                            @endphp
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="fu-progress  text-center" style="   width: 250px;">
                                            <div class="fu-inner">
                                                <div class="fu-percent percent">
                                                    <h1>{{ number_format($sohUnit, '0', ',', '.') }}</h1>
                                                    <h1>Liter</h1>
                                                </div>
                                                <div class="water" style="top: {{ (int) $sisaPersenUnit??0 }}%"></div>
                                                <div class="glare"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 text-center">
                                            <h5 style="margin-top:20px">Maximum Capacity {{ number_format($maxCapacityUnit, '0', ',', '.') }} Liter</h5>

                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card" style="height: 18rem">
                                            <div class="card-table table-responsive">
                                                <table class="table table-vcenter" style="font-size: 12px !important;">
                                                    <thead style="position: sticky; top: 0px; z-index: 10;">
                                                        <th class="text-center">Plant</th>
                                                        <th class="text-end">SOH</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($company->plants as $plant)
                                                            @php
                                                                $sohPlant = 0;
                                                                foreach ($plant->materialStock as $value) {
                                                                    $sohPlant += $value->qty_soh;
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td class="text-center">{{ $plant->plant_code }}</td>
                                                                <td class="text-end">{{ number_format($sohPlant ?? 0, '0', ',', '.') }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            {{--  --}}


        </div>
    </div>
</div>
</div>
<script>
    // var animatePercentChange = function animatePercentChange(newPercent, elem) {
    //     elem = elem || $('.fu-percent span');
    //     const val = parseInt(elem.text(), 10);

    //     if (val !== parseInt(newPercent, 10)) {
    //         let diff = newPercent < val ? -1 : 1;
    //         elem.text(val + diff);
    //         setTimeout(animatePercentChange.bind(null, newPercent, elem), 50);
    //     }
    //};
</script>
