<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
                            <h1>Transfer</h1>
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
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr class="text-nowrap">
                                                <th class="text-center" style="width: 5%">#</th>
                                                <th>Posting</th>
                                                <th>Trans Date</th>
                                                <th>Trans Type</th>
                                                <th>From Company</th>
                                                <th>From Warehouse</th>
                                                <th>To Company</th>
                                                <th>To Warehouse</th>
                                                <th>Transportir</th>
                                                <th>Material</th>
                                                <th>UOM</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($transfers->isEmpty())
                                                {!! dataNotFond(8) !!}
                                            @else
                                                @foreach ($transfers as $idx => $val)
                                                    <tr class="text-nowrap">
                                                        <td class="text-center">{{ ($idx+1) }}</td>
                                                        <td>{{ $val->posting_no }}</td>
                                                        <td>{{ $val->trans_date }}</td>
                                                        <td>{{ $val->trans_type }}</td>
                                                        <td>{{ $val->fromCompany->company_name ?? '' }}</td>
                                                        <td>{{ $val->fromSloc->sloc_name ?? '' }}</td>
                                                        <td>{{ $val->toCompany->company_name ?? '' }}</td>
                                                        <td>{{ $val->toSloc->sloc_name ?? '' }}</td>
                                                        <td>{{ $val->equipments->equipment_description ?? ''}}</td>
                                                        <td>{{ $val->materials->material_description ?? ''}}</td>
                                                        <td class="text-center">{{ $val->uom }}</td>
                                                        <td class="text-end">{{ number_format($val->qty) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer justify-content-between align-items-center">
                                    {{ $transfers->links() }}
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
    @push('scripts')
        <script>
            function checkAll(mainCheckbox) {
                const checkboxes = document.querySelectorAll('.detailCheckbox');

                checkboxes.forEach(checkbox => {
                    checkbox.checked = mainCheckbox.checked;
                });
            }

            async function deleteItem(id) {
                const checkboxes = document.querySelectorAll('.detailCheckbox:checked');
                const selectedIds = [];
                checkboxes.forEach(checkbox => {
                    selectedIds.push(checkbox.value);
                });
                if (selectedIds.length > 0) {
                    const isConfirmed = await sweetDeleted({
                        id: id,
                        title: 'Delete all data selected ? ',
                        textLoadong: '  loading'
                    });
                    if (isConfirmed) {
                        @this.call('delete', selectedIds);
                        const checkboxes = document.querySelectorAll('.detailCheckbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                    }
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Not have data selected",
                        icon: "error"
                    });
                }
            }

            async function postingItem(id) {
                const checkboxes = document.querySelectorAll('.detailCheckbox:checked');
                const selectedIds = [];
                checkboxes.forEach(checkbox => {
                    selectedIds.push(checkbox.value);
                });
                if (selectedIds.length > 0) {
                    const isConfirmed = await sweetPosting({
                        id: id,
                        title: 'Posting all data selected ? ',
                        textLoadong: '  loading'
                    });
                    if (isConfirmed) {
                        @this.call('posting', selectedIds);
                        const checkboxes = document.querySelectorAll('.detailCheckbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                    }
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Not have data selected",
                        icon: "error"
                    });
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
                    @this.call('report',search , company, startDate, endDate);
                }
            }
        </script>
    @endpush
</div>
