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
                            <button type="button" class="btn btn-primary me-2" wire:click="$dispatch('openModal')"
                                data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                    class="fa fa-plus-circle"></i>&nbsp; Create</button>
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openModal')"
                                data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                    class="fa fa-plus-circle"></i>&nbsp; Batch</button>
                        </div>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Filter</h3>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="search">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="name" class="form-label">Issue No</label>
                                        <input wire:model="issue" type="text" class="form-control ">
                                    </div>
                                    <div class="col-4">
                                        <label for="email" class="form-label">Equipment</label>
                                        <input wire:model="equipment" type="text" class="form-control ">
                                    </div>
                                    {{-- <div class="col-4"> --}}
                                    {{-- <label for="role">Role</label> --}}
                                    {{-- <select wire:model="role" class="form-control ">
                                            <option value="">- All Role -</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->description }}</option>
                                            @endforeach
                                        </select> --}}
                                    {{-- </div> --}}
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                                &nbsp; Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form wire:submit.prevent="search">
                                <div class="d-flex">
                                    {{-- <div class="text-muted">
                                        Show
                                        <div class="mx-2 d-inline-block">
                                            <input type="text" class="form-control form-control-sm" value="8"
                                                size="3" aria-label="Invoices count">
                                        </div>
                                        entries
                                    </div> --}}
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="text" class="form-control form-control-sm"
                                                aria-label="Issue No" placeholder="Issue No">
                                        </div>
                                    </div>

                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="text" class="form-control form-control-sm"
                                                aria-label="Equipment No" placeholder="Equipment No">
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
                                {{-- <input wire:model="issue" type="text" class="form-control ">
                                <input wire:model="equipment" type="text" class="form-control "> --}}
                                {{-- <div class="row row-cards">
                                    <div class="col-12">
                                        <input wire:model="issue" type="text" class="form-control col-4">
                                    </div>
                                    <div class="col-4">
                                        <input wire:model="equipment" type="text" class="form-control col-4">
                                    </div>
                                    <div class="col-4">
                                        <button type="submit" class="btn btn-primary"><i
                                                class="fa fa-search col-4"></i>
                                            &nbsp; Filter</button>
                                    </div>

                                </div> --}}
                                {{-- <div class="row">
                                    <div class="col-4">
                                        <label for="name" class="form-label">Issue No</label>
                                        <input wire:model="issue" type="text" class="form-control ">
                                    </div>
                                    <div class="col-4">
                                        <label for="email" class="form-label">Equipment</label>
                                        <input wire:model="equipment" type="text" class="form-control ">
                                    </div> --}}
                                {{-- <div class="col-4"> --}}
                                {{-- <label for="role">Role</label> --}}
                                {{-- <select wire:model="role" class="form-control ">
                                            <option value="">- All Role -</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->description }}</option>
                                            @endforeach
                                        </select> --}}
                                {{-- </div> --}}
                                {{-- </div> --}}
                                {{-- <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                                                &nbsp; Filter</button>
                                        </div>
                                    </div>
                                </div> --}}
                            </form>
                        </div>
                        {{-- <div class="card-body"> --}}
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">#</th>
                                        {{-- <th class="text-center" style="width: 5%">Action</th> --}}
                                        <th>Company</th>
                                        <th>Issue No</th>
                                        <th>Department</th>
                                        <th>Fuelman</th>
                                        <th>Equipment</th>
                                        <th>Qty</th>
                                        {{-- <th class="w-1"></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($issues->isEmpty())
                                        {!! dataNotFond(8) !!}
                                    @else
                                        @foreach ($issues as $issue)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                {{-- <td class="text-nowrap">
                                                        <a id="btn-reset{{ $user->id }}" title="Reset Password User"
                                                            onclick="resetPassword({{ $user->id }})">
                                                            <i class="fa fa-lock"></i>
                                                        </a> &nbsp;
    
                                                        <a id="btn-delete{{ $user->id }}" title="Deleted User"
                                                            onclick="deleteItem({{ $user->id }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a> &nbsp;
    
                                                        <a title="Edit User"
                                                            wire:click="$dispatch('openModal', [{{ $user->id }}])"
                                                            data-bs-toggle="modal" data-bs-target="#modal-large">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td> --}}
                                                <td>{{ $issue->company_id }}</td>
                                                <td>{{ $issue->issue_no }}</td>
                                                <td>{{ $issue->department }}</td>
                                                <td>{{ $issue->fuelman }}</td>
                                                <td>{{ $issue->equipment }}</td>
                                                <td>{{ $issue->details[0]->qty . ' ' . $issue->details[0]->uom_id }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $issues->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('issue.modal-issue')
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
            async function resetPassword(id) {
                const isConfirmed = await sweetReset({
                    id: id
                });
                if (isConfirmed) {
                    @this.call('reset_password', id);
                }
            }
        </script>
    @endpush
</div>
