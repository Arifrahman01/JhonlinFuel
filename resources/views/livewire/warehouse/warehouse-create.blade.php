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
                            {{ $statusModal }} Warehouse</h5>
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
                            <label for="" class="form-label required">Plant</label>
                            <select wire:model="selectedPlant" wire:key="{{ $selectedCompany }}" class="form-select"
                                required>
                                <option value="">-Select Plant-</option>
                                @foreach ($plants as $plant)
                                    <option value="{{ $plant->id }}">
                                        {{ $plant->plant_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="" class="form-label required">Warehouse Code</label>
                            <input type="text" class="form-control" wire:model='warehouseCode'
                                placeholder="Warehouse Code" required
                                @if ($warehouseCodeReadOnly) readonly disabled @endif>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="" class="form-label required">Warehouse Name</label>
                            <input type="text" class="form-control" wire:model='warehouseName'
                                placeholder="Warehouse Name" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="" class="form-label required">Capacity</label>
                            <input type="number" class="form-control" wire:model='capacity'
                                placeholder="Capacity" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal"
                            wire:click="closeModal">Close</button>
                        @canany(['create-master-warehouse', 'edit-master-warehouse'])
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save</button>
                        @endcanany
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
