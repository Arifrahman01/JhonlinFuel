<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
                            <h1>Loader Receipt</h1>  
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
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="date" class="form-control form-control-sm" id="start_date" wire:model="filter_date" aria-label="Start Date" placeholder="Start Date"
                                                value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                               <i class="fa fa-search"></i>  &nbsp; Cari &nbsp;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewire('transaction.create-receipt')

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
        async function postingItem(id, warehouse, date) {
            const isConfirmed = await sweetPosting({
                id: id
            });
            if (isConfirmed) {
                @this.call('posting', id, warehouse, date);
            }
        }

    </script>
@endpush
</div>
