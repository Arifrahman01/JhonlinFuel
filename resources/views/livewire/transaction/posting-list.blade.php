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
                                            <input type="date" class="form-control form-control-sm" id="start_date" onchange="setEndDateMax()" wire:model="start_date" aria-label="Start Date" placeholder="Start Date"
                                                value="{{ $start_date }}">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            s/d
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="date" class="form-control form-control-sm" id="end_date"  wire:model="end_date" aria-label="End Date" placeholder="End Date" value="{{ $end_date }}">
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
                        <div class="table-responsive">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter card-table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 5%">#</th>
                                                    <th>Company</th>
                                                    <th class="text-nowrap">Posting No</th>
                                                    <th>Type</th>
                                                    <th class="text-center">Date</th>
                                                    <th>Fuelman</th>
                                                    <th>Equipment</th>
                                                    <th>Location</th>
                                                    <th>Department</th>
                                                    <th>Activity</th>
                                                    <th class="text-center">Fuel Type</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th>Satistic</th>
                                                    <th class="text-center">Meter Value</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-nowrap">
                                                @if ($transactions->isEmpty())
                                                    {!! dataNotFond(8) !!}
                                                @else
                                                    @foreach ($transactions as $idx => $trans)
                                                        <tr>
                                                            <td>{{ $idx + 1 }}</td>
                                                            <td>{{ $trans->company->company_name }}</td>
                                                            <td>{{ $trans->posting_no }}</td>
                                                            <td>{{ $trans->trans_type }}</td>
                                                            <td class="text-center">{{ $trans->trans_date }}</td>
                                                            <td>{{ $trans->fuelman_name }}</td>
                                                            <td>{{ $trans->equipment_no }}</td>
                                                            <td>{{ $trans->location_name }}</td>
                                                            <td>{{ $trans->department }}</td>
                                                            <td>{{ $trans->activity_name }}</td>
                                                            <td class="text-center">{{ $trans->fuel_type }}</td>
                                                            <td class="text-end">{{ number_format($trans->qty) }}</td>
                                                            <td>{{ $trans->statistic_type }}</td>
                                                            <td class="text-end">{{ number_format($trans->meter_value) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer justify-content-between align-items-center">
                                        {{ $transactions->links() }}
                                    </div>
                                </div>
                            </div>
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
