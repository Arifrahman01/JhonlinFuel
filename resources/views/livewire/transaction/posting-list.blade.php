<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Transaction Posting
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
                                            <input type="date" class="form-control form-control-sm" id="start_date" wire:model="filter_date" aria-label="Start Date" placeholder="Start Date"
                                                value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    {{-- <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            s/d
                                        </div>
                                    </div> --}}
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            {{-- <input type="date" class="form-control form-control-sm" id="end_date" aria-label="End Date" placeholder="End Date" value="{{ date('Y-m-d') }}"> --}}
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                &nbsp; Cari &nbsp;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table id="treegrid" role="treegrid" class="table table-sm table-striped" <colgroup>
                                <col id="treegrid-col1">
                                <col id="treegrid-col2">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 1%">Warehouse/Company</th>
                                        <th class="text-center" style="width: 10%">Date/Type Trans</th>
                                        <th class="text-center" style="width: 10%">Fuelman</th>
                                        <th class="text-center" style="width: 10%">Equipment No</th>
                                        <th class="text-center" style="width: 5%">Location</th>
                                        <th class="text-center" style="width: 5%">Department</th>
                                        <th class="text-center" style="width: 5%">Activity</th>
                                        <th class="text-center" style="width: 5%">Fuel & Oil Type</th>
                                        <th class="text-center" style="width: 10%">Litre Issued</th>
                                        <th class="text-center" style="width: 5%">Statistic Type</th>
                                        <th class="text-center" style="width: 10%">Meter Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($transactions->isEmpty())
                                        {!! dataNotFond(10) !!}
                                    @else
                                        @foreach ($transactions as $idx => $trans)
                                            <tr role="row" aria-level="1" aria-posinset="1" aria-setsize="2" aria-expanded="false">
                                                <td role="gridcell">
                                                    {{ $trans['summary']->posting_no.' - '.$trans['summary']->location_name }}
                                                </td>
                                                <td colspan="6" class="text-nowrap" role="gridcell">{{ $trans['summary']->trans_date }}</td>
                                                <td colspan="2" style="text-align: right">{{ number_format($trans['summary']->total_qty, 2, '.', ',') }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                            @foreach ($trans['details'] as $indx => $detail)
                                                <tr role="row" aria-level="2" aria-posinset="1" aria-setsize="15" class="hidden text-nowrap">
                                                    <td role="gridcell">
                                                        {{ $detail->company_code }}
                                                    </td>
                                                    <td role="gridcell">{{ $detail->trans_type }}</td>
                                                    <td role="gridcell">{{ $detail->fuelman }}</td>
                                                    <td role="gridcell">{{ $detail->equipment_no }}</td>
                                                    <td role="gridcell">{{ $detail->location }}</td>
                                                    <td role="gridcell">{{ $detail->department }}</td>
                                                    <td role="gridcell">{{ $detail->activity }}</td>
                                                    <td role="gridcell"  style="white-space: nowrap;">{{ $detail->fuel_type }}</td>
                                                    <td role="gridcell" style="text-align: right">{{ number_format($detail->qty, 2, '.', ',') }}</td>
                                                    <td role="gridcell">{{ $detail->statistic_type }}</td>
                                                    <td role="gridcell" style="text-align: right">{{ number_format($detail->meter_value, 2, '.', ',') }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                            {{-- {{ $transactions->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('transaction.modal-transaction')
    @push('scripts')
        <script src="./dist/js/custom.js"></script>
        <script>

            function setEndDateMax() {
                var startDate = document.getElementById("start_date").value;
                var endDateField = document.getElementById("end_date");
                if (!startDate) {
                    endDateField.removeAttribute("max");
                    return;
                }
                var maxDate = new Date(startDate);
                maxDate.setDate(maxDate.getDate() + 30);
                var maxDateString = maxDate.toISOString().split('T')[0];
                endDateField.setAttribute("max", maxDateString);
                if (endDateField.value > maxDateString) {
                    endDateField.value = maxDateString;
                }
            }
        </script>
    @endpush
</div>
