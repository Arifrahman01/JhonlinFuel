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
                                            <input type="date" class="form-control form-control-sm" id="start_date"
                                                wire:model="dateFilter" aria-label="Start Date"
                                                placeholder="Start Date">
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
                                            <tr>
                                                <td class="text-center">{{ ($rcvTransfers->currentPage() - 1) * $rcvTransfers->perPage() + $loop->index + 1 }}</td>
                                                <td class="text-center">{{ $rcv->posting_no }}</td>
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
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer justify-content-between align-items-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @push('scripts')
        <script>
            function checkAll(mainCheckbox) {
                const checkboxes = document.querySelectorAll('.detailCheckbox');

                checkboxes.forEach(checkbox => {
                    checkbox.checked = mainCheckbox.checked;
                });
            }
        </script>
    @endpush --}}
</div>
