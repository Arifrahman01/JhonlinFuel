<div wire:ignore.self class="modal modal-blur fade" id="modal-large" tabindex="-1" aria-labelledby="modal-largeLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
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
                <form wire:submit.prevent="store">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-largeLabel">Warehouse Tranfer</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeModalID"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-status-top bg-red mb-2 mt-2">From Warehouse</div><br>
                                <div class="card-body p-1">
                                    <div class="row row-cards mt-1 mb-1">
                                        <div class="col-4">
                                            <label class="form-label required">From Company</label>
                                            <select wire:model.live="selectedFromCompany" class="form-control" required="">
                                                <option value="">-Select Company-</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label for="" class="form-label required">From Location/ Plant
                                                <div wire:loading="" wire:target="selectedFromCompany">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </div>
                                            </label>
                                            <select name="" id="" class="form-control" wire:model.live="selectedFromPlant" required="">
                                                <option value="">-Select Plant-</option>
                                                @foreach ($fromPlants as $fromPlant)
                                                    <option value="{{ $fromPlant->id }}">{{ $fromPlant->plant_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label for="" class="form-label required">From Warehouse
                                                <div wire:loading="" wire:target="selectedFromPlant">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </div>
                                            </label>
                                            <select name="" id="" class="form-control" wire:model="from_warehouse" required="">
                                                <option value="">-Select Warehouse-</option>
                                                @foreach ($fromSlocs as $fromSloc)
                                                    <option value="{{ $fromSloc->id }}">{{ $fromSloc->sloc_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3">
                            <div class="card">
                                <div class="card-status-top bg-red mb-2 mt-2">To Warehouse</div><br>
                                <div class="card-body p-1">
                                    <div class="row row-cards mt-1 mb-1">
                                        <div class="col-6">
                                            <label class="form-label required">To Company</label>
                                            <select wire:model.live="selectedToCompany" class="form-control" required="">
                                                <option value="">-Select Company-</option>
                                                @foreach ($Companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label for="" class="form-label required">To Location/ Plant
                                                <div wire:loading="" wire:target="selectedToCompany">
                                                    <i class="fa fa-spinner fa-spin"></i>
                                                </div>
                                            </label>
                                            <select name="" id="" class="form-control" wire:model.live="selectedToPlant" required="">
                                                <option value="">-Select Plant-</option>
                                                @foreach ($toPlants as $toPlant)
                                                    <option value="{{ $toPlant->id }}">{{ $toPlant->plant_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="" class="form-label required">Notes</label>
                                            <textarea wire:model="notes" rows="3" class="form-control" required>

                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto"  wire:click="closeModal" data-bs-dismiss="modal">Close</button>
                        @canany(['create-master-warehouse', 'edit-master-warehouse'])
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save</button>
                        @endcanany
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
