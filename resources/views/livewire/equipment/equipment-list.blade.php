<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Equipment
                        </div>
                        @can('create-master-equipment')
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
                                            <select wire:model.live="p" wire:key="{{ $c }}"
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
                                                aria-label="Code, Name" placeholder="Equipment" wire:model.live="q">
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
                            <table class="table table-vcenter card-table table-striped table-bordered">
                                <thead>
                                    <tr class="text-nowrap">
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th class="text-center" style="width: 6%">Action</th>
                                        <th class="text-center">Company</th>
                                        <th class="text-center">Plant</th>
                                        <th class="text-center">Equipment No</th>
                                        <th class="text-center">Equipment Desctiption</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($equipments->isEmpty())
                                        {!! dataNotFond(4) !!}
                                    @else
                                        @foreach ($equipments as $idx => $equipment)
                                            <tr class="text-nowrap">
                                                <td class="text-center">{{ $idx + 1 }}</td>
                                                <td class="text-center">
                                                    
                                                    @can('delete-master-equipment')
                                                        <a id="btn-delete{{ $equipment->id }}" title="Delete equipment"
                                                            onclick="deleteItem({{ $equipment->id }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a> &nbsp;
                                                    @endcan
                                                    @can('edit-master-equipment')
                                                        <a title="Edit equipment"
                                                            wire:click="$dispatch('openCreate', [{{ $equipment->id }}])"
                                                            data-bs-toggle="modal" data-bs-target="#modal-large">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                                <td>{{ $equipment->company->company_name }}</td>
                                                <td>{{ $equipment->plant->plant_name }}</td>
                                                <td>{{ $equipment->equipment_no }}</td>
                                                <td>{{ $equipment->equipment_description }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $equipments->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('equipment.equipment-create')
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
