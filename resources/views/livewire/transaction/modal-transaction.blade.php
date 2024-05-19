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
                    <form wire:submit.prevent="storeData({{ $dataTmp->id ?? '' }})">
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
                                        {{-- @dd($dataTmp); --}}
                                        @foreach ($companiesModal as $comp)
                                            <option value="{{ $comp->company_code }}" {{ selected($comp->company_code, $dataTmp ? $dataTmp->company_code : '') }}>{{ $comp->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Full Warehouse</label>
                                    <select name="" id="" class="form-control" wire:model="full_warehouse" required>
                                        <option value="">-Select Warehouse-</option>
                                        <option value="1">Warehouse 1</option>
                                    </select>
                                
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Trans Type</label>
                                    <select name="" id="" class="form-control" wire:model="trans_type" required>
                                        <option value="">-Trans Type-</option>
                                        <option value="ISS" {{ selected('ISS', $dataTmp ? $dataTmp->trans_type : '') }}>ISS</option>
                                        <option value="RCV" {{ selected('RCV', $dataTmp ? $dataTmp->trans_type : '') }}>RCV</option>
                                        <option value="TRF" {{ selected('TRF', $dataTmp ? $dataTmp->trans_type : '') }}>TRF</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Issue Date</label>
                                    <input type="date" class="form-control" wire:model='trans_date'  value="{{ $dataTmp ? $dataTmp->trans_date : '' }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Fuelman</label>
                                    <select name="" id="" class="form-control" wire:model="fuelman" required>
                                        <option value="">-Fuelman-</option>
                                        <option value="123">Contoh Fuelman</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Equipment Number</label>
                                    <select name="" id="" class="form-control" wire:model="equipment_no" required>
                                        <option value="">-Equipment Number-</option>
                                        <option value="123">Contoh Equipment</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Location</label>
                                    <select name="" id="" class="form-control" wire:model="location" required>
                                        <option value="">-Select Location-</option>
                                        <option value="12">Location 1</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Department</label>
                                    <select name="" id="" class="form-control" wire:model="department" required>
                                        <option value="">-Select Department-</option>
                                        <option value="12">Department Contoh</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Activity</label>
                                    <select name="" id="" class="form-control" wire:model="activity" required>
                                        <option value="">-Select Activity-</option>
                                        <option value="11">Activity Contoh</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Fuel Type</label>
                                    <select name="" id="" class="form-control" wire:model="fuel_type" required>
                                        <option value="">-Select Fuel Type-</option>
                                        @foreach ($material as $mat)
                                            <option value="{{ $mat->id }}" {{ selected($mat->id, $dataTmp ? $dataTmp->fuel_type : '') }} >{{ $mat->material_description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" wire:model='qty' value="{{ $dataTmp ? $dataTmp->qty : '' }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Statistic Type</label>
                                    <select name="" id="" class="form-control" wire:model="statistic_type" required>
                                        <option value="">-Select Type-</option>
                                        <option value="1">Type Contoh</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Meter Value</label>
                                    <input type="number" class="form-control" wire:model='meter_value' value="{{ $dataTmp ? $dataTmp->meter_value : '' }}" required>
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
