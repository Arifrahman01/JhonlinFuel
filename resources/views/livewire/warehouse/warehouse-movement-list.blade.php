<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Warehouse Movement
                        </div>
                        @can('create-master-warehouse')
                            <div class="col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" wire:click="$dispatch('openCreate')"
                                    data-bs-toggle="modal" data-bs-target="#modal-large"><i
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
                                <div class="d-flex">
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="text" class="form-control form-control-sm"
                                                aria-label="Warehouse Code, Warehouse Name"
                                                placeholder="Warehouse Code, Warehouse Name" wire:model="q">
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
                            <table class="table table-vcenter card-table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 6%">Action</th>
                                        <th class="text-center">Company</th>
                                        <th class="text-center">Plant</th>
                                        <th class="text-center">Warehouse Code</th>
                                        <th class="text-center">Warehouse Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                               
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

                        <div class="card-footer justify-content-between align-items-center">
                         
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('warehouse.warehouse-movement-create')
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
