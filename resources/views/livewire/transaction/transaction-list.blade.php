<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Issue
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary me-2" wire:click="$dispatch('openModal')" data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                    class="fa fa-plus"></i>&nbsp; Create</button>
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openModal')" data-bs-toggle="modal" data-bs-target="#modal-large"><i
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
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="date" class="form-control form-control-sm" aria-label="Start Date" placeholder="Start Date">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            s/d
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="date" class="form-control form-control-sm" aria-label="End Date" placeholder="End Date">
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
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr class="text-nowrap"> 
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th>Company</th>
                                        <th>Warehouse</th>
                                        <th>Trans Type</th>
                                        <th>Issue Date</th>
                                        <th>Fuelman</th>
                                        <th>Location</th>
                                        <th>Department</th>
                                        <th>Activity</th>
                                        <th>Fuel & Oil Type</th>
                                        <th>Litre Issued</th>
                                        <th>Meter Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($transaction)
                                        {!! dataNotFond(8) !!}
                                    @else
                                        @foreach ($transaction as $trans)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                            {{ $transaction->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('transaction.modal-transaction')
    @push('scripts')
        <script>
            async function deleteItem(id) {
                const isConfirmed = await sweetDeleted({
                    id: id
                });
                if (isConfirmed) {
                    @this.call('delete', id);
                }
            }
        </script>
    @endpush
</div>
