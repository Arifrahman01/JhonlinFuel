<div wire:ignore.self class="modal modal-blur fade" id="modal-large" tabindex="-1" aria-labelledby="modal-largeLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
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
                @if ($statusModal == 'upload')
                    <form wire:submit.prevent="storeLoader" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-largeLabel">Upload Receipt Transfer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                wire:click="closeModal" id="closeModalID"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="row-cards">
                                    <div class="mb-3">
                                        <label for="fileLoader" class="form-label">File Loader</label>
                                        <input wire:model="fileLoader" class="form-control"
                                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                            id="fileLoader" name="fileLoader" onchange="loadFile()" type="file"
                                            required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal"
                                wire:click="closeModal">Close</button>
                            <button type="submit" id="btn-submit-upload" class="btn btn-primary"><i
                                    class="fa fa-save"></i>&nbsp; Upload</button>
                        </div>
                    </form>
                @else
                    <form wire:submit.prevent="storeData({{ '' }})">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-largeLabel">{{ $statusModal . ' Receipt Transfer' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                wire:click="closeModal" id="closeModalID"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row row-cards">
                                <div class="col-4">
                                    <label for="" class="form-label required">Trans Date</label>
                                    <input type="date" class="form-control" wire:model='transDate' required>
                                </div>


                                <div class="col-4">
                                    <label class="form-label required">From Company</label>
                                    <select wire:model.live="selectedFromCompany" class="form-select" required>
                                        <option value="">-Select Company-</option>
                                        @foreach ($companies as $comp)
                                            <option value="{{ $comp->company_code }}">{{ $comp->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label required">From Warehouse</label>
                                    @if ($isLoadingFromWarehouse)
                                        <div class="input-icon mb-3">
                                            <input type="text" value="" class="form-control"
                                                placeholder="Loading…" disabled>
                                            <span class="input-icon-addon">
                                                <div class="spinner-border spinner-border-sm text-muted" role="status">
                                                </div>
                                            </span>
                                        </div>
                                    @else
                                        <select wire:model.live="selectedFromWarehouse"
                                            wire:key="{{ $selectedFromCompany }}" class="form-select" required>
                                            <option value="">-Select Warehouse-</option>
                                            @foreach ($fromSlocs as $fromSloc)
                                                <option value="{{ $fromSloc->sloc_code }}">
                                                    {{ $fromSloc->sloc_name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-4">
                                    <label class="form-label required">To Company</label>
                                    <select wire:model.live="selectedToCompany" class="form-select" required>
                                        <option value="">-Select Company-</option>
                                        @foreach ($companies as $comp)
                                            <option value="{{ $comp->company_code }}">{{ $comp->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label required">To Warehouse</label>
                                    @if ($isLoadingToWarehouse)
                                        <div class="input-icon mb-3">
                                            <input type="text" value="" class="form-control"
                                                placeholder="Loading…" disabled>
                                            <span class="input-icon-addon">
                                                <div class="spinner-border spinner-border-sm text-muted"
                                                    role="status">
                                                </div>
                                            </span>
                                        </div>
                                    @else
                                        <select wire:model.live="selectedToWarehouse"
                                            wire:key="{{ $selectedToCompany }}" class="form-select" required>
                                            <option value="">-Select Warehouse-</option>
                                            @foreach ($toSlocs as $toSloc)
                                                <option value="{{ $toSloc->sloc_code }}">
                                                    {{ $toSloc->sloc_name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                                <div class="col-4">
                                    <label class="form-label required">Transportir</label>
                                    <input type="text" class="form-control" wire:model="transportir"
                                        placeholder="Transportir" required>
                                </div>
                                <div class="col-4">
                                    <label class="form-label required">Material</label>
                                    <select wire:model="selectedMaterial" class="form-select" required>
                                        <option value="">-Select Material-</option>
                                        @foreach ($materials as $material)
                                            <option value="{{ $material->material_code }}">
                                                {{ $material->material_description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-4">
                                    <label class="form-label required">Qty</label>
                                    <input type="number" wire:model="qty" class="form-control" placeholder="Qty"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn me-auto" data-bs-dismiss="modal"
                                wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;
                                {{ 'Submit' }}</button>
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
