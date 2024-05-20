<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Management User
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openModal')" data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                class="fa fa-plus-circle"></i>&nbsp; Create</button>
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
                                        <label for="name">Name</label>
                                        <input wire:model="name" type="text" class="form-control ">
                                    </div>
                                    <div class="col-4">
                                        <label for="email">Email</label>
                                        <input wire:model="email" type="text" class="form-control ">
                                    </div>
                                    <div class="col-4">
                                        <label for="role">Role</label>
                                        <select wire:model="role" class="form-control ">
                                            <option value="">- All Role -</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> &nbsp; Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th class="text-center" style="width: 5%">Action</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($users->isEmpty())
                                        {!! dataNotFond(8) !!}
                                    @else
                                        @foreach ($users as $idx => $user)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td class="text-nowrap">
                                                    <a id="btn-reset{{ $user->id }}" title="Reset Password User" onclick="resetPassword({{ $user->id }})">
                                                        <i class="fa fa-lock"></i>
                                                    </a> &nbsp;

                                                    <a id="btn-delete{{ $user->id }}" title="Deleted User" onclick="deleteItem({{ $user->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a> &nbsp;

                                                    <a title="Edit User" wire:click="$dispatch('openModal', [{{ $user->id }}])" data-bs-toggle="modal" data-bs-target="#modal-large">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->role->description ?? ''}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('user.modal-user')
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