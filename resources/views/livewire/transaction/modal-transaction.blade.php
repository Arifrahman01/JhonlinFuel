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
                    {{-- <form wire:submit.prevent="store" enctype="x-www-form-urlencoded"> --}}
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
                    <form wire:submit.prevent="storeData({{ $dataTmp->id ?? '' }})">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-largeLabel">Edit Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row row-cards">
                                <div class="col-4">
                                    <label class="form-label">Company</label>
                                    <select wire:model.live="selectedCompany" class="form-control" required>
                                        <option value="">-Select Company-</option>
                                        @foreach ($companiesModal as $comp)
                                            <option value="{{ $comp->company_code }}" {{ selected($comp->company_code, $dataTmp ? $dataTmp->company_code : '') }}>{{ $comp->company_name }}</option>
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
                                            <option value="{{ $plant->id }}"  {{ selected($plant->id, $dataTmp ? $dataTmp->selectedlocation : '') }}>
                                                {{ $plant->id . ' - ' . $plant->plant_name }}
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
                                    <select name="" id="" class="form-control" wire:model="fuel_warehouse" required>
                                        <option value="">-Select Warehouse-</option>
                                        @foreach ($slocs as $sloc)
                                            <option value="{{ $sloc->sloc_code }}" {{ selected($sloc->sloc_code, $dataTmp ? $dataTmp->fuel_warehouse : '') }}>
                                                {{ $sloc->sloc_code . ' - ' . $sloc->sloc_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Trans Type</label>
                                    <select name="" id="" class="form-control" wire:model="trans_type" required>
                                        <option value="">-Trans Type-</option>
                                        <option value="ISS" {{ selected('ISS', $dataTmp ? $dataTmp->trans_type : '') }}>ISS</option>
                                        <option value="TRF" {{ selected('TRF', $dataTmp ? $dataTmp->trans_type : '') }}>TRF</option>
                                        <option value="IRS" {{ selected('IRS', $dataTmp ? $dataTmp->trans_type : '') }}>IRS</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Issue Date</label>
                                    <input type="date" class="form-control" wire:model='trans_date' value="{{ $dataTmp ? $dataTmp->trans_date : '' }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Fuelman</label>
                                    <select name="" id="" class="form-control" wire:model="fuelman" required>
                                        <option value="">-Fuelman-</option>
                                        @foreach ($fuelmans as $fuelMan)
                                        <option value="{{ $fuelMan->nik }}">{{ $fuelMan->nik.' - '.$fuelMan->name }}</option>
                                        @endforeach
                                        <option value="123">Contoh Fuelman</option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Equipment Number</label>
                                    <select name="" id="" class="form-control" wire:model="equipment_no" required>
                                        <option value="">-Equipment Number-</option>
                                        @foreach ($equipments as $equip)
                                            <option value="{{ $equip->equipment_no }}" {{ selected($equip->equipment_no, $dataTmp ? $dataTmp->equipment_no : '') }}>
                                                {{ $equip->equipment_no . ' - ' . $equip->equipment_description }}</option>
                                        @endforeach
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
                                        @foreach ($activitys as $activity)
                                            <option value="{{ $activity->id }}">{{ $activity->id . ' - ' . $activity->activity_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Fuel Type</label>
                                    <select name="" id="" class="form-control" wire:model="fuel_type" required>
                                        <option value="">-Select Fuel Type-</option>
                                        @foreach ($material as $mat)
                                            <option value="{{ $mat->id }}" {{ selected($mat->id, $dataTmp ? $dataTmp->fuel_type : '') }}>{{ $mat->material_description }}</option>
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
                                        <option value="HM" {{ selected('HM', $dataTmp ? $dataTmp->statistic_type : '') }}>HM</option>
                                        <option value="KM" {{ selected('KM', $dataTmp ? $dataTmp->statistic_type : '') }}>KM</option>
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
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; {{ $dataTmp ? 'Update' : 'Create' }}</button>
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
