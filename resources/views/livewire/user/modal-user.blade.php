<div wire:ignore.self class="modal modal-blur fade" id="modal-large" tabindex="-1" aria-labelledby="modal-largeLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            @if ($loading)
                <div class="modal-header">
                    <h4 class="modal-title">Please Wait</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        wire:click="closeModal" id="closeModalID"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <br><br>
                        <i class="fa fa-spinner fa-spin fa-5x"></i>
                        <h5>Please Wait...</h5>
                        <br><br>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal"
                        wire:click="closeModal">Close</button>
                </div>
            @else
                <form wire:submit.prevent="storeUser({{ $user->id ?? '' }})">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-largeLabel">
                            {{ $user ? 'Edit ' . $user->name ?? '' : 'Tambah User' }} </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="closeModal" id="closeModalID"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row row-cards">
                            <div class="col-6">
                                <label class="form-label required">Name</label>
                                <input type="text" wire:model="name" class="form-control" placeholder="Name"
                                    required>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label required">Username</label>
                                    <input type="username" wire:model="username" class="form-control"
                                        placeholder="Username" required>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-header card-header-light">
                                <h3 class="card-title form-label required">Roles</h3>
                            </div>
                            <div class="card-body row">
                                <div class="col-6 mb-3">
                                    <label class="form-label required">Role</label>
                                    <select wire:model.live="selectedRole" id="selectRole" class="form-select" required>
                                        <option value="">-Select Role-</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id . '-' . $role->role_name }}">
                                                {{ $role->role_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6"></div>
                                <div class="row">
                                    <label class="form-label required">Company</label>
                                    @foreach ($companies as $company)
                                        <div class="col-6">
                                            <label class="form-check form-check-inline">
                                                <input wire:model="selectedCompany.{{ $company->id }}"
                                                    class="form-check-input" type="checkbox">
                                                <span class="form-check-label">{{ $company->company_name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="col-12 my-3">
                                    <button type="button" class="btn btn-primary w-100"
                                        wire:click="addData">Add</button>
                                </div>


                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="w-1"></th>
                                                <th>Role</th>
                                                <th>Company</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles_ as $role_)
                                                {{-- {{ dd($role_) }} --}}
                                                <tr>
                                                    <td class="align-top">
                                                        <a title="Delete Item"
                                                            wire:click="deleteItem({{ $loop->index }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </td>
                                                    <td class="align-top">{{ $role_['role'] }}</td>
                                                    <td class="align-top">
                                                        <ul>
                                                            @foreach ($role_['company'] as $rCompany)
                                                                <li>
                                                                    {{ $rCompany }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{-- </div> --}}
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal"
                            wire:click="closeModal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;
                            Save</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
