<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
                            <h1>Receipt Transfer</h1>
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
                                            <input type="text" id="search" class="form-control form-control-sm" wire:model.live="q" placeholder="Posting">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
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
                            <table class="table table-vcenter card-table table-striped table-bordered"
                                style="table-layout: auto; min-width:100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">Posting No</th>
                                        <th class="text-center">Trans Type</th>
                                        <th class="text-center">Trans Date</th>
                                        <th class="text-center">From Company Code</th>
                                        <th class="text-center">From Warehouse</th>
                                        <th class="text-center">To Company Code</th>
                                        <th class="text-center">To Warehouse</th>
                                        <th class="text-center">Transportir</th>
                                        <th class="text-center">Material Code</th>
                                        <th class="text-center">Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($rcvTransfers->isEmpty())
                                        {!! dataNotFond(6) !!}
                                    @else
                                        @foreach ($rcvTransfers as $rcv)
                                            <tr class="text-nowrap">
                                                <td class="text-center">
                                                    {{ ($rcvTransfers->currentPage() - 1) * $rcvTransfers->perPage() + $loop->index + 1 }}
                                                </td>
                                                <td class="text-center">{{ $rcv->posting_no }}</td>
                                                <td class="text-center">{{ $rcv->trans_type }}</td>
                                                <td class="text-center">{{ $rcv->trans_date }}</td>
                                                <td class="text-left">{{ $rcv->fromCompany->company_name ?? '' }}</td>
                                                <td class="text-left">{{ $rcv->fromWarehouse->sloc_name ?? '' }}</td>
                                                <td class="text-left">{{ $rcv->toCompany->company_name ?? '' }}</td>
                                                <td class="text-left">{{ $rcv->toWarehouse->sloc_name ?? '' }}</td>
                                                <td class="text-left">{{ $rcv->equipments->equipment_description ?? '' }}</td>
                                                <td class="text-center">{{ $rcv->materials->material_description }}</td>
                                                <td style="text-align: right;">
                                                    {{ number_format($rcv->qty, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $rcvTransfers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        async function downloadExcel(id) {
            const isConfirmed = await sweetPosting({
                id: id,
                title: 'Download Report ? ',
                textLoadong: '  loading'
            });
            if (isConfirmed) {
                const search = document.getElementById('search').value;
                const company = document.getElementById("company").value;
                const startDate = document.getElementById("start_date").value;
                const endDate = document.getElementById("end_date").value;
                
                @this.call('report', search, company, startDate, endDate);
            }
        }
    </script>
@endpush
</div>
