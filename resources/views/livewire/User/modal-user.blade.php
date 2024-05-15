<div wire:ignore.self class="modal modal-blur fade" id="modal-large" tabindex="-1" aria-labelledby="modal-largeLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            @if ($loading)
                <div class="modal-header">
                    <h4 class="modal-title">Please Wait</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID"></button>
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
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                </div>
            @else
                <form wire:submit.prevent="updateUser({{ $user->id ?? '' }})">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-largeLabel">{{ $user ? 'Edit ' . $user->name ?? '' : 'Tambah User' }} </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID// Get the button element
var closeModalButton = document.getElementById('closeModalID');
"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row row-cards">
                            <div class="col-6">
                                <label class="form-label">Name</label>
                                <input type="text" wire:model="name" class="form-control" placeholder="" value="{{ $user->name ?? '' }}" required>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" wire:model="email" class="form-control" placeholder="Username" value="{{ $user->email }}" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Role</label>
                                {{-- <input type="text" class="form-control" placeholder="" value="{{ $user->name ?? '' }}"> --}}
                                <select wire:model="role" class="form-control" required>
                                    <option value="">-Select Role-</option>
                                    @foreach ($rolesModal as $role)
                                        <option value="{{ $role->id }}" {{ selected($role->id, $user ? $user->role_id : '') }}>{{ $role->description }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
