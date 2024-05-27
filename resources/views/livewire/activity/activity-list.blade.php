<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Activity
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openCreate')" data-bs-toggle="modal" data-bs-target="#modal-large"><i
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
                                            <input type="text" class="form-control form-control-sm" aria-label="Code, Name" placeholder="Code, Name" wire:model.live="q">
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
                                        <th class="text-center">Code</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($activitys->isEmpty())
                                        {!! dataNotFond(4) !!}
                                    @else
                                        @foreach ($activitys as $idx => $activity)
                                            <tr class="text-nowrap">
                                                <td class="text-center">{{ ($idx+1) }}</td>
                                                <td class="text-center">
                                                    <a id="btn-delete{{ $activity->id }}" title="Delete activity" onclick="deleteItem({{ $activity->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a> &nbsp;

                                                    <a title="Edit activity" wire:click="$dispatch('openCreate', [{{ $activity->id }}])" data-bs-toggle="modal" data-bs-target="#modal-large">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td>{{ $activity->company->company_name }}</td>
                                                <td>{{ $activity->activity_code }}</td>
                                                <td>{{ $activity->activity_name }}</td>
                                                <td>{{ $activity->notes }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $activitys->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('activity.activity-create')
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
