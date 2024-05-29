<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
                            <h1>Receipt</h1>
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
                                                    <option value="{{ $company->id }}">
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
                            <table class="table table-vcenter card-table table-striped table-bordered" style="table-layout: auto; min-width:100%;">
                                <thead>
                                    <tr class="text-nowrap">
                                        <th class="text-center">#</th>
                                        <th>Posting</th>
                                        <th>Company</th>
                                        <th>Trans Type</th>
                                        <th>Trans Date</th>
                                        <th>PO NO</th>
                                        <th>DO NO</th>
                                        <th>Location</th>
                                        <th>Warehouse</th>
                                        <th>Transportir</th>
                                        <th>Material</th>
                                        <th>UOM</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody class="text-nowrap">
                                    @if ($receipts->isEmpty())
                                        {!! dataNotFond(6) !!}
                                    @else
                                        @foreach ($receipts as $rcv)
                                            <tr>
                                                <td class="text-center">
                                                    {{ ($receipts->currentPage() - 1) * $receipts->perPage() + $loop->index + 1 }}
                                                </td>
                                                <td>{{ $rcv->posting_no }}</td>
                                                <td>{{ $rcv->company->company_name ?? '' }}</td>
                                                <td>{{ $rcv->trans_type }}</td>
                                                <td>{{ $rcv->trans_date }}</td>
                                                <td>{{ $rcv->po_no }}</td>
                                                <td>{{ $rcv->do_no }}</td>
                                                <td>{{ $rcv->plants->plant_name ?? '' }}</td>
                                                <td>{{ $rcv->slocs->sloc_name ?? '' }}</td>
                                                <td>{{ $rcv->equipments->equipment_description ?? '' }}</td>
                                                <td>{{ $rcv->materials->material_description  ?? ''}}</td>
                                                <td class="text-center">{{ $rcv->uom }}</td>
                                                <td class="text-end">{{ number_format($rcv->qty) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $receipts->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
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
