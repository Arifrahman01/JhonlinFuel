<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Warehouse Transfer
                        </div>
                        @can('create-master-warehouse')
                            <div class="col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" wire:click="$dispatch('openCreate')" data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                        class="fa fa-plus-circle"></i>&nbsp;
                                    Create</button>
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
                                {{-- <div class="d-flex">
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="text" class="form-control form-control-sm" aria-label="Warehouse Code, Warehouse Name" placeholder="Warehouse Code, Warehouse Name"
                                                wire:model="q">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                &nbsp; Cari &nbsp;
                                            </button>
                                        </div>
                                    </div>
                                </div> --}}
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-sm table-bordered" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th class="text-center" rowspan="2" style="width: 5%">No</th>
                                        <th class="text-center" rowspan="2">Transfer Date</th>
                                        <th class="text-center" rowspan="2">Transfer By</th>
                                        <th class="text-center" colspan="2">From Transfer</th>
                                        <th class="text-center" colspan="2">To Transfer</th>
                                        <th class="text-center" rowspan="2">Warehouse Code</th>
                                        <th class="text-center" rowspan="2">Warehouse Name</th>
                                        <th class="text-center" rowspan="2">Notes</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Company</th>
                                        <th class="text-center">Plant</th>
                                        <th class="text-center">Company</th>
                                        <th class="text-center">Plant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($slocTransfers->isEmpty())
                                        {!! dataNotFond(4) !!}
                                    @else
                                        @foreach ($slocTransfers as $index => $slocTransfer)
                                        {{-- @dd($slocTransfer) --}}
                                            <tr class="text-nowrap">
                                                <td class="text-center">{{ ($slocTransfers->currentPage() - 1) * $slocTransfers->perPage() + $loop->index + 1 }}</td>
                                                <td class="text-center">{{$slocTransfer->trans_date }}</td>
                                                <td class="text-right">{{ $slocTransfer->user->name }}</td>
                                                <td class="text-right">{{ $slocTransfer->fromCompany->company_name ?? ''}}</td>
                                                <td class="text-right">{{ $slocTransfer->fromPlant->plant_name ?? ''}}</td>
                                                <td class="text-right">{{ $slocTransfer->toCompany->company_name ?? ''}}</td>
                                                <td class="text-right">{{ $slocTransfer->toPlant->plant_name ?? ''}}</td>
                                                <td class="text-right">{{ $slocTransfer->sloc->sloc_code ?? '' }}</td>
                                                <td class="text-right">{{ $slocTransfer->sloc->sloc_name ?? '' }}</td>
                                                <td class="text-right"> {{ $slocTransfer->notes }}</td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                            {{ $slocTransfers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('warehouse.warehouse-transfer-create')
    @push('scripts')
        <script>
            document.addEventListener('livewire:init', function() {
                Livewire.on('logData', data => {
                    console.log(data);
                });
            });

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
