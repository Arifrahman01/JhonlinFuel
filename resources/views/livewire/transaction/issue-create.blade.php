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
                    <form wire:submit.prevent="store" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-largeLabel">Upload Transaction</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="row-cards">
                                    <div class="mb-3">
                                        <label for="fileLoader" class="form-label">File Loader</label>
                                        <input wire:model="fileLoader" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                            id="fileLoader" name="fileLoader" onchange="loadFile()" type="file" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                            <button type="submit" id="btn-submit-upload" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Upload</button>
                        </div>
                    </form>
                @else
                    <form wire:submit.prevent="storeData()">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-largeLabel">Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row row-cards">
                                <div class="col-4">
                                    <label class="form-label">Company</label>
                                    <select wire:model.live="selectedCompany" class="form-control" required>
                                        <option value="">-Select Company-</option>
                                        @foreach ($companies as $comp)
                                            <option value="{{ $comp->company_code }}">{{ $comp->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="" class="form-label">Location/Plant
                                        <div wire:loading wire:target="selectedCompany">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    </label>
                                    <select name="" id="" class="form-control" wire:model.live="selectedlocation" required>
                                        <option value="">-Select Location-</option>
                                        @foreach ($plants as $plant)
                                            <option value="{{ $plant->plant_code }}" >
                                                {{ $plant->plant_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label for="" class="form-label">Fuel Warehouse
                                        <div wire:loading wire:target="selectedlocation">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    </label>
                                    <select name="" id="" class="form-control" wire:model="warehouse" required>
                                        <option value="">-Select Warehouse-</option>
                                        @foreach ($slocs as $sloc)
                                            <option value="{{ $sloc->sloc_code }}" >
                                                {{ $sloc->sloc_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Department
                                        <div wire:loading wire:target="selectedCompany">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    </label>
                                    <select name="" id="" class="form-control" wire:model="department" required>
                                        <option value="">-Select Department-</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->department_code }}" >{{ $dept->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Fuelman
                                        <div wire:loading wire:target="selectedlocation">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    </label>
                                    <select name="" id="" class="form-control" wire:model="fuelman" required>
                                        <option value="">-Fuelman-</option>
                                        @foreach ($fuelmans as $fuelMan)
                                        <option value="{{ $fuelMan->nik }}">{{ $fuelMan->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Trans Type</label>
                                    <input type="text" class="form-control" wire:model="trans_type" required value="ISS">
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Issue Date</label>
                                    <input type="date" class="form-control" wire:model='trans_date'  required>
                                </div>
                               
                                <div class="col-3">
                                    <label for="" class="form-label">Activity
                                        <div wire:loading wire:target="selectedCompany">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    </label>
                                    <select name="" id="" class="form-control" wire:model="activity" required>
                                        <option value="">-Select Activity-</option>
                                        @foreach ($activitys as $activity)
                                            <option value="{{ $activity->activity_code }}">{{ $activity->activity_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-3">
                                    <label for="" class="form-label">Equipment Number</label>
                                    <input type="text" class="form-control" wire:model="equipment_no" required>
                                </div>

                               
                              
                                <div class="col-3">
                                    <label for="" class="form-label">Fuel Type</label>
                                    <select name="" id="" class="form-control" wire:model="material_code" required>
                                        <option value="">-Select Fuel Type-</option>
                                        {{-- @dd($materials); --}}
                                        @foreach ($materials as $mat)
                                            <option value="{{ $mat->material_code }}">{{ $mat->material_description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" wire:model='qty'  required>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Statistic Type</label>
                                    <select name="" id="" class="form-control" wire:model="statistic_type" required>
                                        <option value="">-Select Type-</option>
                                        <option value="HM">HM</option>
                                        <option value="KM">KM</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Meter Value</label>
                                    <input type="number" class="form-control" wire:model='meter_value' equired>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save</button>
                        </div>
                    </form>
                @endif



            @endif
        </div>
    </div>
</div>
<script>
    function loadFile() {
        const btnUpload = document.getElementById('btn-submit-upload');
        btnUpload.innerHTML = "Loading...<i class='fa fa-spinner fa-spin'></i>";
        btnUpload.disabled = true;
    }
</script>
