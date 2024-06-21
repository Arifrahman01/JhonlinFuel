<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Warehouse
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
                                            <select wire:model.live="c" class="form-select form-select-sm">
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
                                            <select wire:model.live="p" wire:key="c"
                                                class="form-select form-select-sm">
                                                <option value="">-Select Plant-</option>
                                                @foreach ($plants as $plant)
                                                    <option value="{{ $plant->id }}">
                                                        {{ $plant->plant_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                                        <th class="text-center">Capacity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($warehouses->isEmpty())
                                        {!! dataNotFond(2) !!}
                                    @else
                                        @foreach ($warehouses as $warehouse)
                                            <tr>
                                                <td class="text-center">
                                                    @can('delete-master-warehouse')
                                                        @if (!$warehouse->hasDataById() && !$warehouse->hasDataByCode())
                                                            <a id="btn-delete{{ $warehouse->id }}" title="Delete Warehouse"
                                                                onclick="deleteItem({{ $warehouse->id }})">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a> &nbsp;
                                                        @endif
                                                    @endcan
                                                    @can('edit-master-warehouse')
                                                        <a title="Edit Warehouse"
                                                            wire:click="$dispatch('openCreate', [{{ $warehouse->id }}])"
                                                            data-bs-toggle="modal" data-bs-target="#modal-large">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                                <td>{{ data_get($warehouse, 'company.company_name') }}</td>
                                                <td>{{ data_get($warehouse, 'plant.plant_name') }}</td>
                                                <td>{{ $warehouse->sloc_code }}</td>
                                                <td>{{ $warehouse->sloc_name }}</td>
                                                <td>{{ number_format($warehouse->capacity,'0',',','.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $warehouses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('warehouse.warehouse-create')
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
