<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Role
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openCreate')"
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
                                            <input type="text" class="form-control form-control-sm"
                                                aria-label="Role Code, Role Name" placeholder="Role Code, Role Name"
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
                                        <th class="text-center">Role Code</th>
                                        <th class="text-center">Role Name</th>
                                        <th class="text-center">Otorisasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($roles->isEmpty())
                                        {!! dataNotFond(2) !!}
                                    @else
                                        @foreach ($roles as $role)
                                            <tr role="row" aria-level="1" aria-posinset="1" aria-setsize="1"
                                                aria-expanded="false">
                                                <td class="text-center align-top">
                                                    @if (!$role->hasDataById() && !$role->hasDataByCode())
                                                        <a id="btn-delete{{ $role->id }}" title="Delete Role"
                                                            onclick="deleteItem({{ $role->id }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a> &nbsp;
                                                    @endif

                                                    <a title="Edit Role"
                                                        wire:click="$dispatch('openCreate', [{{ $role->id }}])"
                                                        data-bs-toggle="modal" data-bs-target="#modal-large">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td class="align-top">{{ $role->role_code }}</td>
                                                <td class="align-top">{{ $role->role_name }}</td>
                                                <td class="align-top">
                                                    <ul>
                                                        @foreach ($role->permissions as $otorisasi)
                                                            <li>
                                                                {{ $otorisasi->permission_name }}
                                                            </li>
                                                        @endforeach
                                                    </ul>

                                                </td>
                                                {{-- <td>{{ implode(',', data_get($role, 'permissions.*.permission_name')) }}
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $roles->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('role.role-create')
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
