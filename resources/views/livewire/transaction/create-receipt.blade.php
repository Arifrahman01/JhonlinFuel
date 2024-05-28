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
                    <form wire:submit.prevent="storeLoader" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-largeLabel">Upload Receipt</h5>
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
                    <form wire:submit.prevent="storeData({{ $dataReceipt->id ?? '' }})">
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
                                        @foreach ($companies as $comp)
                                            <option value="{{ $comp->company_code }}" {{ selected($comp->company_code, $dataReceipt ? $dataReceipt->company_code : '') }}>{{ $comp->company_name }}</option>
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
                                            <option value="{{ $plant->plant_code }}">
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
                                            <option value="{{ $sloc->sloc_code }}">
                                                {{  $sloc->sloc_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Issue Date</label>
                                    <input type="date" class="form-control" wire:model='trans_date' value="{{ $dataReceipt ? $dataReceipt->trans_date : '' }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">PO NO</label>
                                    <input type="text" class="form-control" wire:model='po_no' value="{{ $dataReceipt ? $dataReceipt->qty : '' }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">DO NO</label>
                                    <input type="text" class="form-control" wire:model='do_no' value="{{ $dataReceipt ? $dataReceipt->qty : '' }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Transportir</label>
                                    <input type="text" class="form-control" wire:model='transportir' value="{{ $dataReceipt ? $dataReceipt->qty : '' }}" required>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Material Code</label>
                                    <select name="" id="" class="form-control"  wire:model='material_code' required>
                                        <option value="">-Select Material-</option>
                                        @foreach ($materials as $mat)
                                        <option value="{{ $mat->material_code }}">{{ $mat->material_description }}</option>
                                            
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label for="" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" wire:model='qty' value="" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; {{ $dataReceipt ? 'Update' : 'Create' }}</button>
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
