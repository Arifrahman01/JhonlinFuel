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
                <form wire:submit.prevent="storeIssue({{ $issue->id ?? '' }})">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-largeLabel">
                            {{ $issue ? 'Edit ' . $issue->issue_no ?? '' : 'Tambah Issue' }} </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="closeModal" id="closeModalID"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row row-cards">
                            <div class="col-6">
                                <label class="form-label">Company</label>
                                {{-- <input type="text" class="form-control" placeholder="" value="{{ $user->name ?? '' }}"> --}}
                                <select wire:model="company" class="form-control" required>
                                    <option value="">-Select Company-</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ selected($company->id, $issue ? $issue->id : '') }}>
                                            {{ $company->company_name }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Department</label>
                                    <input type="text" wire:model="department" class="form-control"
                                        placeholder="Department" value="{{ $issue->department ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Activity</label>
                                    <input type="text" wire:model="activity" class="form-control"
                                        placeholder="Activity" value="{{ $issue->activity ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Fuelman</label>
                                    <input type="text" wire:model="fuelman" class="form-control"
                                        placeholder="Fuelman" value="{{ $issue->fuelman ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Equipment</label>
                                    <input type="text" wire:model="equipment" class="form-control"
                                        placeholder="Equipment" value="{{ $issue->equipment ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Driver</label>
                                    <input type="text" wire:model="driver" class="form-control" placeholder="Driver"
                                        value="{{ $issue->equipment_driver ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="mb-3">
                                    <label class="form-label">Material</label>
                                    <input type="text" wire:model="material" class="form-control"
                                        placeholder="Material" value="{{ $issue->material ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="mb-3">
                                    <label class="form-label">Qty</label>
                                    <input type="text" wire:model="qty" class="form-control" placeholder="Qty"
                                        value="{{ $issue->qty ?? '' }}" required>
                                </div>
                            </div>
                            {{-- <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" wire:model="email" class="form-control" placeholder="Username"
                                        value="{{ $user->email ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Role</label>
                                <select wire:model="role" class="form-control" required>
                                    <option value="">-Select Role-</option>
                                    @foreach ($rolesModal as $role)
                                        <option value="{{ $role->id }}"
                                            {{ selected($role->id, $user ? $user->role_id : '') }}>
                                            {{ $role->description }}</option>
                                    @endforeach
                                </select>

                            </div> --}}
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal"
                            wire:click="closeModal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
