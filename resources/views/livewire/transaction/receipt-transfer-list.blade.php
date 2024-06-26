<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
                            <h2>Loader Receipt Transfer</h2>
                        </div>
                        @can('create-loader-receipt-transfer')
                            <div class="col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary me-2" wire:click="$dispatch('openCreate')" data-bs-toggle="modal" data-bs-target="#modal-large"><i class="fa fa-plus"></i>&nbsp;
                                    Create</button>
                                <button type="button" class="btn btn-primary" wire:click="$dispatch('openUpload')" data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                        class="fa fa-file-excel"></i>&nbsp; Upload</button>
                            </div>
                        @endcan
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
                                            <input type="date" class="form-control form-control-sm" id="start_date" wire:model="filter_date" aria-label="Start Date" placeholder="Start Date">
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
                            @can('delete-loader-receipt-transfer')
                                <div class="ms-2 d-inline-block">
                                    <button id="btn-delete{{ -1 }}" class="btn btn-danger btn-sm" onclick="deleteItem({{ -1 }})">
                                        <i class="fa fa-trash"></i> &nbsp; Delete &nbsp;
                                    </button>
                                </div>
                            @endcan
                            @can('posting-loader-receipt-transfer')
                                <div class="ms-2 d-inline-block">
                                    <button id="btn-posting{{ -1 }}" class="btn btn-warning btn-sm" onclick="postingItem({{ -1 }})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                            <path
                                                d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z">
                                            </path>
                                        </svg>
                                        &nbsp; Posting &nbsp;
                                    </button>
                                </div>
                            @endcan
                        </div>

                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped table-bordered" style="table-layout: auto; min-width:100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">
                                            <input class="form-check-input m-0 align-middle" type="checkbox" onchange="checkAll(this)" aria-label="Select all invoices">
                                        </th>
                                        <th class="text-center" style="width: 5%">Action</th>
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
                                            <tr>
                                                <td class="text-center">
                                                    <input class="form-check-input m-0 align-middle detailCheckbox" value="{{ $rcv->id }}" type="checkbox">
                                                </td>
                                                <td class="text-center text-nowrap">
                                                    @can('edit-loader-receipt-transfer')
                                                        @if (!$rcv->posting_no)
                                                            <a title="Edit Receipt Transfer" wire:click="$dispatch('openCreate', [{{ $rcv->id }}])" data-bs-toggle="modal"
                                                                data-bs-target="#modal-large">
                                                                <i class="fas fa-edit"></i>
                                                            </a> &nbsp;
                                                        @endif
                                                    @endcan
                                                    @if (!$rcv->error_status == null)
                                                        <i href="#" class="fa fa-info-circle" style="color: red" title="{{ $rcv->error_status }}">
                                                        </i>
                                                    @endif
                                                </td>
                                                <td class="text-center">{{ $rcv->trans_type }}</td>
                                                <td class="text-center">{{ $rcv->trans_date }}</td>
                                                <td class="text-center">{{ $rcv->from_company_code }}</td>
                                                <td class="text-center">{{ $rcv->from_warehouse }}</td>
                                                <td class="text-center">{{ $rcv->to_company_code }}</td>
                                                <td class="text-center">{{ $rcv->to_warehouse }}</td>
                                                <td class="text-center">{{ $rcv->transportir }}</td>
                                                <td class="text-center">{{ $rcv->material_code }}</td>
                                                <td style="text-align: right;">
                                                    {{ number_format($rcv->qty, 0, ',', '.') }}</td>
                                            </tr>
                                            {{-- @foreach ($adjust->details as $detail)
                                                <tr role="row" aria-level="2" aria-posinset="2" aria-setsize="3"
                                                    class="hidden">
                                                    <td></td>
                                                    <td>{{ $detail->plant->plant_name }}</td>
                                                    <td>{{ $detail->sloc->sloc_name }}</td>
                                                    <td style="text-align: right">
                                                        {{ number_format($detail->origin_qty, 0, ',', '.') }}</td>
                                                    <td style="text-align: right">
                                                        {{ number_format($detail->adjust_qty, 0, ',', '.') }}</td>
                                                    <td style="text-align: right">
                                                        {{ number_format(toNumber($detail->origin_qty) + toNumber($detail->adjust_qty), 0, ',', '.') }}
                                                    </td>
                                                    <td>{{ $detail->notes }}</td>
                                                </tr>
                                            @endforeach --}}
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

    @livewire('transaction.receipt-transfer-create')

    @push('scripts')
        <script>
            function checkAll(mainCheckbox) {
                const checkboxes = document.querySelectorAll('.detailCheckbox');

                checkboxes.forEach(checkbox => {
                    checkbox.checked = mainCheckbox.checked;
                });
            }

            // async function deleteItem(id) {
            //     const isConfirmed = await sweetDeleted({
            //         id: id
            //     });
            //     if (isConfirmed) {
            //         @this.call('delete', id);
            //     }
            // }

            async function deleteItem(id) {
                if (id != -1) {
                    const isConfirmed = await sweetDeleted({
                        id: id
                    });
                    if (isConfirmed) {
                        @this.call('delete', [id]);
                    }
                    return;
                }

                const checkboxes = document.querySelectorAll('.detailCheckbox:checked');
                const selectedIds = [];
                checkboxes.forEach(checkbox => {
                    selectedIds.push(checkbox.value);
                });
                if (selectedIds.length > 0) {
                    const isConfirmed = await sweetDeleted({
                        id: id,
                        title: 'Delete all data selected ? ',
                        textLoadong: 'loading'
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

            // async function postingItem(id, warehouse, date) {
            //     const isConfirmed = await sweetPosting({
            //         id: id
            //     });
            //     if (isConfirmed) {
            //         @this.call('posting', id, warehouse, date);
            //     }
            // }

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
                        textLoadong: 'loading'
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
        </script>
    @endpush
</div>
