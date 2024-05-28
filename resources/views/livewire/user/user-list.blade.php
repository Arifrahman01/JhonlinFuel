<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            User
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openModal')"
                                data-bs-toggle="modal" data-bs-target="#modal-large"><i
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
                        <div class="card-header">
                            <form wire:submit="search">
                                <div class="d-flex">
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="text" class="form-control form-control-sm"
                                                aria-label="Name, Username" placeholder="Name, Username" wire:model="q">
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
                            <table class="table card-table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 6%">Action</th>
                                        {{-- <th class="text-center" style="width: 5%">Action</th> --}}
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Username</th>
                                        <th class="text-center">Role</th>
                                        <th class="text-center">Company</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($users->isEmpty())
                                        {!! dataNotFond(2) !!}
                                    @else
                                        @foreach ($users as $user)
                                            @if ($user->username == 'sa')
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->username }}</td>
                                                    <td>
                                                        {{ data_get($user, 'roles.0.role_name') }}
                                                    </td>
                                                    <td>
                                                        All
                                                    </td>
                                                </tr>
                                            @elseif (data_get($user, 'roles.0.role_code') == 'sa')
                                                <tr>
                                                    <td>
                                                        <a id="btn-delete{{ $user->id }}" title="Delete User"
                                                            onclick="deleteItem({{ $user->id }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a> &nbsp;

                                                        <a title="Edit User"
                                                            wire:click="$dispatch('openModal', [{{ $user->id }}])"
                                                            data-bs-toggle="modal" data-bs-target="#modal-large">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->username }}</td>
                                                    <td>
                                                        {{ data_get($user, 'roles.0.role_name') }}
                                                    </td>
                                                    <td>
                                                        All
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td rowspan="{{ count($user->roles) }}">
                                                        <a id="btn-delete{{ $user->id }}" title="Delete User"
                                                            onclick="deleteItem({{ $user->id }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a> &nbsp;

                                                        <a title="Edit User"
                                                            wire:click="$dispatch('openModal', [{{ $user->id }}])"
                                                            data-bs-toggle="modal" data-bs-target="#modal-large">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    </td>
                                                    <td rowspan="{{ count($user->roles) }}">{{ $user->name }}</td>
                                                    <td rowspan="{{ count($user->roles) }}">{{ $user->username }}</td>
                                                    <td class="text-start">
                                                        {{ data_get($user, 'roles.0.role_name') }}
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                {{ data_get($user, 'roles.0.pivot.companies.0.company_name') }}
                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                                @foreach ($user->roles as $role)
                                                    @if ($loop->index > 0)
                                                        <tr>
                                                            <td class="text-start">
                                                                {{ $role->role_name }}
                                                            </td>
                                                            <td>
                                                                <ul>
                                                                    @foreach ($role->pivot->companies as $company__)
                                                                        <li>
                                                                            {{ $company__->company_name }}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </td>

                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                            {{-- {{ dd($user->roles) }} --}}
                                            {{-- <tr>
                                                <td class="text-center" rowspan="{{ count($user->roles) }}">
                                                    @if (!$company->hasDataById() && !$company->hasDataByCode())
                                                        <a id="btn-delete{{ $company->id }}" title="Delete Company"
                                                            onclick="deleteItem({{ $company->id }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a> &nbsp;
                                                    @endif

                                                    <a title="Edit Company"
                                                        wire:click="$dispatch('openCreate', [{{ $company->id }}])"
                                                        data-bs-toggle="modal" data-bs-target="#modal-large">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td rowspan="{{ count($user->roles) }}">{{ $user->name }}</td>
                                                <td rowspan="{{ count($user->roles) }}">{{ $user->username }}</td>
                                                <td>
                                                    {{ data_get($user, 'roles.0.role_name') }}
                                                </td>
                                            </tr>
                                            @if (data_get($user, 'roles.0.role_code') == 'sa')
                                                @foreach ($user->roles as $role)
                                                    @if ($loop->index > 0)
                                                        <tr>
                                                            <td>
                                                                {{ $role->role_name }}
                                                            </td>
                                                            <td>

                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif --}}
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

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
            function checkAll(mainCheckbox) {
                const checkboxes = document.querySelectorAll('.check-company');

                checkboxes.forEach(checkbox => {
                    checkbox.checked = mainCheckbox.checked;
                });
            }

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
