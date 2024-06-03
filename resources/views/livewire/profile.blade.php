<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Account Settings
                    </h2>
                </div>
            </div>
        </div>
    </div>


    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-3 border-end">
                        <div class="card-body">
                            <h4 class="subheader">Business settings</h4>
                            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active" id="v-tabs-profile" data-bs-toggle="pill" href="#tabs-profile" role="tab" aria-controls="tabs-profile"
                                    aria-selected="true"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                    </svg>
                                    My Account
                                </a>
                                <a class="nav-link" id="v-pills-role" data-bs-toggle="pill" href="#tabs-role" role="tab" aria-controls="tabs-role"
                                    aria-selected="false"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-tool">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" />
                                    </svg> &nbsp;
                                    Role
                                </a>
                                <a class="nav-link" id="v-pills-activity-tab" data-bs-toggle="pill" href="#tabs-company" role="tab" aria-controls="tabs-company"
                                    aria-selected="false"><!-- Download SVG icon from http://tabler-icons.io/i/activity -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-building-warehouse">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 21v-13l9 -4l9 4v13" />
                                        <path d="M13 13h4v8h-10v-6h6" />
                                        <path d="M13 21v-9a1 1 0 0 0 -1 -1h-2a1 1 0 0 0 -1 1v3" />
                                    </svg> &nbsp;
                                    Access Company
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-profile" role="tabpanel" aria-labelledby="v-tabs-profile">
                                    <form wire:submit.prevent="updateProfile">
                                        <div class="card-body">
                                            <h2 class="mb-4">My Account</h2>
                                            <h3 class="card-title">Profile Details</h3>
                                            <div class="row align-items-center">
                                                <div class="col-auto"><span class="avatar avatar-xl" style="background-image: url(./static/avatars/default.jpg)"></span>
                                                </div>
                                                <div class="col-auto"><a href="#" class="btn">
                                                        Change avatar
                                                    </a></div>
                                                <div class="col-auto"><a href="#" class="btn btn-ghost-danger">
                                                        Delete avatar
                                                    </a></div>
                                            </div>
                                            <h3 class="card-title mt-4"> Profile</h3>
                                            <div class="row g-3">
                                                <div class="col-md">
                                                    <div class="form-label required">Name</div>
                                                    <input type="text" class="form-control" wire:model="name" required>
                                                </div>
                                                <div class="col-md">
                                                    <div class="form-label">Username</div>
                                                    <input type="text" class="form-control" wire:model="username">
                                                </div>
                                            </div>
                                            <h3 class="card-title mt-4">Email</h3>
                                            <div>
                                                <div class="row g-2">
                                                    <div class="col-auto">
                                                        <input type="email" class="form-control" wire:model="email" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <h3 class="card-title mt-4">Password</h3>
                                            <div>
                                                <a title="Edit User" class="btn" data-bs-toggle="modal" data-bs-target="#modal-sm">
                                                    <i class="fas fa-lock"></i> &nbsp; Set new password
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-transparent mt-auto">
                                            <div class="btn-list justify-content-end">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;
                                                    Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tabs-role" role="tabpanel" aria-labelledby="v-pills-role">
                                    <div class="card-body">
                                        <h2 class="mb-4">Account Role</h2>
                                        <h3 class="card-title">Role Detail</h3>
                                        <div class="table-responsive">
                                            <table class="table table-vcenter card-table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Role</th>
                                                        <th class="text-center">Permission</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $user)
                                                        @foreach ($user->roles as $idx => $role)
                                                            <tr>
                                                                <td>{{ $role->role_name }}</td>
                                                                @if ($role->role_code == 'sa')
                                                                    <td>
                                                                        All
                                                                    </td>
                                                                @else
                                                                    <td>
                                                                        @php
                                                                            $prevMenuId = null;
                                                                        @endphp
                                                                        @foreach ($role->permissions as $permission)
                                                                            @if ($permission->menu_id != $prevMenuId)
                                                                                @if (!is_null($prevMenuId))
                                                                                    <br>
                                                                                @endif
                                                                                @php
                                                                                    $prevMenuId = $permission->menu_id;
                                                                                @endphp
                                                                            @endif
                                                                            @if (explode('-', $permission->permission_code)[0] == 'view')
                                                                                <span class="badge bg-blue text-blue-fg mb-2">{{ $permission->permission_name }}</span>
                                                                            @elseif (explode('-', $permission->permission_code)[0] == 'create')
                                                                                <span class="badge bg-azure text-azure-fg mb-2">{{ $permission->permission_name }}</span>
                                                                            @elseif (explode('-', $permission->permission_code)[0] == 'edit')
                                                                                <span class="badge bg-lime text-lime-fg mb-2">{{ $permission->permission_name }}</span>
                                                                            @elseif (explode('-', $permission->permission_code)[0] == 'delete')
                                                                                <span class="badge bg-red text-red-fg mb-2">{{ $permission->permission_name }}</span>
                                                                            @elseif (explode('-', $permission->permission_code)[0] == 'posting')
                                                                                <span class="badge bg-orange text-orange-fg mb-2">{{ $permission->permission_name }}</span>
                                                                            @endif
                                                                        @endforeach
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-company" role="tabpanel" aria-labelledby="v-pills-activity-tab">
                                    <div class="card-body">
                                        <h2 class="mb-4">Access Company</h2>
                                        <h3 class="card-title">Detail Company</h3>
                                        <div class="table-responsive">
                                            <table class="table table-vcenter card-table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Role</th>
                                                        <th class="text-center">Company Code</th>
                                                        <th class="text-center">Company Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($users as $user)
                                                        @if ($user->username == 'sa')
                                                            <tr>
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
                                                                    {{ data_get($user, 'roles.0.role_name') }}
                                                                </td>
                                                                <td>
                                                                    All
                                                                </td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td class="text-start">
                                                                    {{ data_get($user, 'roles.0.role_name') }}
                                                                </td>
                                                                <td>
                                                                    <ul>
                                                                        @foreach (data_get($user, 'roles.0.pivot.companies') as $company__)
                                                                            <li>
                                                                                {{ $company__->company_code }}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </td>
                                                                <td>
                                                                    <ul>
                                                                        @foreach (data_get($user, 'roles.0.pivot.companies') as $company__)
                                                                            <li>
                                                                                {{ $company__->company_name }}
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal modal-blur fade" id="modal-sm" tabindex="-1" aria-labelledby="modal-largeLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <form wire:submit.prevent="changePassword">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-largeLabel"> Change Password </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 mb-2">
                            <label class="form-label required">Old Password</label>
                            <input type="text" wire:model="oldPassword" class="form-control" placeholder="Old Password" required>
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label required">New Password</label>
                            <input type="password" wire:model="newPassword" class="form-control" placeholder="New Password" minlength="6" required>
                        </div>
                        <div class="col-12 mbp-2">
                            <label class="form-label required">Comfirm Password</label>
                            <input type="password" wire:model="confirmPassword" class="form-control" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" wire:click="closeModal" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;
                            Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
