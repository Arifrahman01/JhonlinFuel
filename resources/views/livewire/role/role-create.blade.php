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
                <form wire:submit.prevent="store">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-largeLabel">
                            {{ $statusModal }} Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="closeModal" id="closeModalID"></button>
                    </div>
                    <div class="modal-body row">
                        <div class="col-6 mb-3">
                            <label for="" class="form-label required">Role Code</label>
                            <input type="text" class="form-control" placeholder="Role Code" wire:model='roleCode'
                                required>
                        </div>
                        <div class="col-6 mb-3">
                            <label for="" class="form-label required">Role Name</label>
                            <input type="text" class="form-control" placeholder="Role Name" wire:model='roleName'
                                required>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="" class="form-label required">Otorisasi</label>
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; ">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="w-1" {{-- style="position: sticky; top: 0; z-index: 1; border: 1px solid #dee2e6;" --}}>
                                            </th>
                                            <th {{-- style="position: sticky; top: 0; z-index: 1; border: 1px solid #dee2e6;" --}}>
                                                Menu</th>
                                            <th {{-- style="position: sticky; top: 0; z-index: 1; border: 1px solid #dee2e6;" --}}>
                                                Otorisasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- {{ dd($permissions) }} --}}
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td>
                                                    {{-- <a title="Delete Item" wire:click="deleteItem({{ $loop->index }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a> --}}
                                                    <input wire:model="otorisasi.{{ $permission->id }}"
                                                        class="form-check-input m-0 align-middle" type="checkbox"
                                                        aria-label="Select Otorisasi">
                                                </td>
                                                <td>{{ data_get($permission, 'menu.menu_name') }}</td>
                                                <td>{{ $permission->permission_name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
