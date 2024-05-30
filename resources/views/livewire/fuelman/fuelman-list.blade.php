<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Fuelman
                        </div>
                        @can('create-master-fuelman')
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
                                                aria-label="NIK, Name" placeholder="NIK, Name" wire:model="q">
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
                                        {{-- <th class="text-center" style="width: 5%">Action</th> --}}
                                        <th class="text-center">Company</th>
                                        <th class="text-center">Plant</th>
                                        <th class="text-center">NIK</th>
                                        <th class="text-center">Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($fuelmans->isEmpty())
                                        {!! dataNotFond(2) !!}
                                    @else
                                        @foreach ($fuelmans as $fuelman)
                                            <tr>
                                                <td class="text-center">
                                                    @can('delete-master-fuelman')
                                                        @if (!$fuelman->hasDataById() && !$fuelman->hasDataByNik())
                                                            <a id="btn-delete{{ $fuelman->id }}" title="Delete Fuelman"
                                                                onclick="deleteItem({{ $fuelman->id }})">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a> &nbsp;
                                                        @endif
                                                    @endcan

                                                    @can('edit-master-fuelman')
                                                        <a title="Edit Fuelman"
                                                            wire:click="$dispatch('openCreate', [{{ $fuelman->id }}])"
                                                            data-bs-toggle="modal" data-bs-target="#modal-large">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                                <td>{{ data_get($fuelman, 'company.company_name') }}</td>
                                                <td>{{ data_get($fuelman, 'plant.plant_name') }}</td>
                                                <td>{{ $fuelman->nik }}</td>
                                                <td>{{ $fuelman->name }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $fuelmans->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('fuelman.fuelman-create')
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
            // async function resetPassword(id) {
            //     const isConfirmed = await sweetReset({
            //         id: id
            //     });
            //     if (isConfirmed) {
            //         @this.call('reset_password', id);
            //     }
            // }
        </script>
    @endpush
</div>
