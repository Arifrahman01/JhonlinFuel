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
                            {{ $statusModal }} Equipment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="closeModal" id="closeModalID"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-12 mb-3">
                            <label for="" class="form-label required">Company</label>
                            <select wire:model.live="selectedCompany" class="form-select" required>
                                <option value="">-Select Company-</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">
                                        {{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="" class="form-label required">Plant
                                <div wire:loading wire:target="selectedCompany">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                            </label>
                            <select wire:model="plant" wire:key="{{ $selectedCompany }}" class="form-select" required>
                                <option value="">-Select Plant-</option>
                                @foreach ($plants as $plant)
                                    <option value="{{ $plant->id }}">
                                        {{ $plant->plant_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="" class="form-label required">Equipment No</label>
                            <input type="text" class="form-control" wire:model="equipmentNo" placeholder="Code"  @if ($readOnly) readonly disabled @endif
                                required oninput="this.value = this.value.toUpperCase()">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="" class="form-label required">Equipment Desription</label>
                            <input type="text" class="form-control" wire:model='equipmentDesc' placeholder="Name"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal"
                            wire:click="closeModal">Close</button>
                        @canany(['create-master-equipment', 'edit-master-equipment'])
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save</button>
                        @endcanany
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
