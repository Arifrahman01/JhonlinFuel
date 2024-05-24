<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
                            <h1>Loader Transfer</h1>  
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary me-2" wire:click="$dispatch('openCreate')" data-bs-toggle="modal" data-bs-target="#modal-large"><i class="fa fa-plus"></i>&nbsp;
                                Create</button>
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openUpload')" data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                    class="fa fa-file-excel"></i>&nbsp; Upload</button>
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
                                    <div class="ms-auto">
                                        <div class="d-inline-flex">
                                            <input type="text" class="form-control form-control-sm me-2" wire:model="filter_search" aria-label="Search Label" placeholder="Search:Posting">
                                            <input type="date" class="form-control form-control-sm" id="start_date" wire:model="filter_date" aria-label="Start Date" placeholder="Start Date"
                                                value="{{ date('Y-m-d') }}"> &nbsp;

                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="ms-2 d-inline-block">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fa fa-search"></i> &nbsp; Cari &nbsp;
                                            </button>
                                        </div>


                                    </div>
                                </div>
                            </form>
                            <div class="ms-2 d-inline-block">
                                <button id="btn-delete{{ -1 }}" class="btn btn-danger btn-sm" onclick="deleteItem({{ -1 }})">
                                    <i class="fa fa-trash"></i> &nbsp; Delete &nbsp;
                                </button>
                            </div>
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
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="table-responsive">
                                    <table class="table table-vcenter card-table table-striped">
                                        <thead>
                                            <tr class="text-nowrap">
                                                <th class="text-center" style="width: 5%">
                                                    <input class="form-check-input m-0 align-middle" type="checkbox" onchange="checkAll(this)" aria-label="Select all invoices">
                                                </th>
                                                <th class="text-center" style="width: 5%">Action</th>
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
                                                        <td class="text-center">
                                                            <input class="form-check-input m-0 align-middle detailCheckbox" value="{{ $val->id }}" type="checkbox">
                                                        </td>
                                                        <td class="text-nowrap">
                                                            @if (!$val->posting_no)
                                                                <a id="btn-delete{{ $val->id }}" title="Deleted User" onclick="deleteItem({{ $val->id }})">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </a> &nbsp;
                                                                <a title="Edit User" wire:click="$dispatch('openCreate', [{{ $val->id }}])" data-bs-toggle="modal" data-bs-target="#modal-large">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            @else
                                                            @endif
                                                        </td>
                                                        <td>{{ $val->trans_type }}</td>
                                                        <td>{{ $val->from_company_code }}</td>
                                                        <td>{{ $val->from_warehouse }}</td>
                                                        <td>{{ $val->to_company_code }}</td>
                                                        <td>{{ $val->to_warehouse }}</td>
                                                        <td>{{ $val->transportir }}</td>
                                                        <td>{{ $val->material_code }}</td>
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


    @livewire('transaction.create-transfer')

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
    </script>
@endpush
</div>
