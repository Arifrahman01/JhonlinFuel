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
                @if ($statusModal == 'upload')
                    <form wire:submit.prevent="store" enctype="x-www-form-urlencoded">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-largeLabel">Upload Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="row-cards">
                                    <div class="mb-3">
                                        <label for="fileLoader" class="form-label">File Loader</label>
                                        <input class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="ChangeTess(this)"
                                            id="fileLoader" name="fileLoader" wire:model="fileLoader" type="file" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Upload</button>
                        </div>
                    </form>
                @else
                    <form wire:submit.prevent="store" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-largeLabel">Edit Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row row-cards">
                                <div class="col-3">
                                    <label class="form-label">Company</label>
                                    <select name="" id="" wire:model="company_code" class="form-control" required>
                                        <option value="">-Select Company-</option>
                                        @foreach ($companiesModal as $comp)
                                            <option value="{{ $comp->company_code }}">{{ $comp->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Full Warehouse</label>
                                    <select name="" id="" class="form-control" wire:model="full_warehouse">
                                        <option value="">-Select Warehouse-</option>
                                    </select>
                                
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Trans Type</label>
                                    <select name="" id="" class="form-control" wire:model="trans_type">
                                        <option value="">-Trans Type-</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Issue Date</label>
                                    <input type="date" class="form-control" wire:model='trans_date'>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Fuelman</label>
                                    <select name="" id="" class="form-control" wire:model="fuelman" required>
                                        <option value="">-Fuelman-</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Equipment Number</label>
                                    <select name="" id="" class="form-control" wire:model="equipment_no" required>
                                        <option value="">-Equipment Number-</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Location</label>
                                    <select name="" id="" class="form-control" wire:model="location" required>
                                        <option value="">-Select Location-</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Department</label>
                                    <select name="" id="" class="form-control" wire:model="department" required>
                                        <option value="">-Select Department-</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Activity</label>
                                    <select name="" id="" class="form-control" wire:model="activity" required>
                                        <option value="">-Select Activity-</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Fuel Type</label>
                                    <select name="" id="" class="form-control" wire:model="fuel_type" required>
                                        <option value="">-Select Fuel Type-</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" wire:model='qty'>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Statistic Type</label>
                                    <select name="" id="" class="form-control" wire:model="statistic_type" required>
                                        <option value="">-Select Type-</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Meter Value</label>
                                    <input type="number" class="form-control" wire:model='meter_value'>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Upload</button>
                        </div>
                    </form>
                @endif



            @endif
        </div>
    </div>
</div>
