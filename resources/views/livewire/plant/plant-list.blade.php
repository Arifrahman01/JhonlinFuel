<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Plant
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openModal')"
                                data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                    class="fa fa-plus-circle"></i>&nbsp;
                                Create</button>
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
                                            <select wire:model="c" class="form-select form-select-sm">
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
                                            <input type="text" class="form-control form-control-sm"
                                                aria-label="Plant Code, Plant Name" placeholder="Plant Code, Plant Name"
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
                                        <th class="text-center">Plant Code</th>
                                        <th class="text-center">Plant Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($plants->isEmpty())
                                        {!! dataNotFond(2) !!}
                                    @else
                                        @foreach ($plants as $plant)
                                            <tr>
                                                <td class="text-center">
                                                    @if (!$plant->hasDataById() && !$plant->hasDataByCode())
                                                        <a id="btn-delete{{ $plant->id }}" title="Delete Plant"
                                                            onclick="deleteItem({{ $plant->id }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a> &nbsp;
                                                    @endif

                                                    <a title="Edit Plant"
                                                        wire:click="$dispatch('openCreate', [{{ $plant->id }}])"
                                                        data-bs-toggle="modal" data-bs-target="#modal-large">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td>{{ data_get($plant, 'company.company_name') }}</td>
                                                <td>{{ $plant->plant_code }}</td>
                                                <td>{{ $plant->plant_name }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $plants->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('plant.plant-create')
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
