<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
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
                                        <div class="ms- d-inline-block">
                                            <select wire:model.live="c" id="company" class="form-select form-select-sm">
                                                <option value="">-Select Company-</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->company_code }}">
                                                        {{ $company->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                            <div class="ms-2 d-inline-block">
                                <button id="btn-posting{{ -1 }}" class="btn btn-warning btn-sm" onclick="downloadExcel({{ -1 }})">
                                    <i class="fas fa-file-excel"></i> &nbsp; Excel
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter card-table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" style="width: 5%">#</th>
                                                    <th class="text-center">Company</th>
                                                    <th class="text-nowrap">Posting No</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Location</th>
                                                    <th class="text-center">Warehouse</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">Fuelman</th>
                                                    <th class="text-center">Equipment</th>
                                                    <th class="text-center">Department</th>
                                                    <th class="text-center">Activity</th>
                                                    <th class="text-center">Fuel Type</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">Satistic</th>
                                                    <th class="text-center">Meter Value</th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-nowrap">
                                                @if ($issues->isEmpty())
                                                    {!! dataNotFond(8) !!}
                                                @else
                                                    @foreach ($issues as $idx => $trans)
                                                        <tr>
                                                            <td>{{ ($issues->currentPage() - 1) * $issues->perPage() + $loop->index + 1 }}</td>
                                                            <td>{{ $trans->company->company_name }}</td>
                                                            <td class="text-center">{{ $trans->posting_no }}</td>
                                                            <td class="text-center">{{ $trans->trans_date }}</td>
                                                            <td>{{ $trans->plants->plant_name ?? '' }}</td>
                                                            <td>{{ $trans->slocs->sloc_name ?? '' }}</td>
                                                            <td  class="text-center">{{ $trans->trans_type }}</td>
                                                            <td>{{ $trans->fuelmans->name }}</td>
                                                            <td>{{ $trans->equipments->equipment_description ?? '' }}</td>
                                                            <td>{{ $trans->departments->department_name ?? '' }}</td>
                                                            <td>{{ $trans->activitys->activity_name }}</td>
                                                            <td class="text-center">{{ $trans->materials->material_description }}</td>
                                                            <td class="text-end">{{ number_format($trans->qty) }}</td>
                                                            <td  class="text-center">{{ $trans->statistic_type }}</td>
                                                            <td class="text-end">{{ number_format($trans->meter_value) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer justify-content-between align-items-center">
                                        {{ $issues->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                            {{-- {{ $issues->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            function setEndDateMax() {
                // var startDate = document.getElementById("start_date").value;
                // var endDateField = document.getElementById("end_date");
                // if (!startDate) {
                //     endDateField.removeAttribute("max");
                //     return;
                // }
                // var maxDate = new Date(startDate);
                // maxDate.setDate(maxDate.getDate() + 30);
                // var maxDateString = maxDate.toISOString().split('T')[0];
                // endDateField.setAttribute("max", maxDateString);
                // if (endDateField.value > maxDateString) {
                //     endDateField.value = maxDateString;
                // }
            }

            async function downloadExcel(id) {
                const isConfirmed = await sweetPosting({
                    id: id,
                    title: 'Download Report ? ',
                    textLoadong: '  loading'
                });
                if (isConfirmed) {
                    const company = document.getElementById("company").value;
                    const startDate = document.getElementById("start_date").value;
                    const endDate = document.getElementById("end_date").value;
                    
                    @this.call('report', company, startDate, endDate);
                }
            }
        </script>
    @endpush
</div>
