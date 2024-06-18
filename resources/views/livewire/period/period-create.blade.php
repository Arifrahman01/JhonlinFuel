<div wire:ignore.self class="modal modal-blur fade" id="modal-large" tabindex="-1" aria-labelledby="modal-largeLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
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
                            {{ $statusModal }} Period</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="closeModal" id="closeModalID"></button>
                    </div>
                    <div class="row modal-body">
                        <div class="col-12 mb-3">
                            <label class="form-label required">Period Name</label>
                            <input type="text" class="form-control" placeholder="Period Name" wire:model='periodName'
                                required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label required">Start Date</label>
                            <input type="date" class="form-control" placeholder="Start Date" wire:model='startDate'
                                required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label required">End Date</label>
                            <input type="date" class="form-control" placeholder="End Date" wire:model='endDate'
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal"
                            wire:click="closeModal">Close</button>
                        {{-- @canany(['create-master-period', 'edit-master-period']) --}}
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save</button>
                        {{-- @endcanany --}}
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
