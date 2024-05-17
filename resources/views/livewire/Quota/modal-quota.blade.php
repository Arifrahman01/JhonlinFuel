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
                <form wire:submit.prevent="storeUser({{ $data->id ?? '' }})">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-largeLabel">{{ $data ? 'Edit ' . $data->name ?? '' : 'Tambah data' }} </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row row-cards">
                            <div class="col-3">
                                <label class="form-label">Company</label>
                                <select name="" id="" class="form-control" wire:model="company_id" required>
                                    <option value="">-Select Company-</option>
                                    @foreach ($companyModal as $comp)
                                        <option value="{{ $comp->id }}">{{ '[' . $comp->company_code . '] - ' . $comp->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label class="form-label">Request Number</label>
                                    <input type="text" wire:model="request_no" class="form-control" placeholder="Request Number" value="{{ $data->request_no ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label class="form-label">Quantity</label>
                                    <input type="number" wire:model="qty" class="form-control" placeholder="Quantity" value="{{ $data->qty ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label class="form-label">Periode</label>
                                    <select name="" id="" class="form-control" wire:model="period_id" required>
                                        <option value="">-Select Period-</option>
                                        @foreach ($periodeModal as $period)
                                            <option value="{{ $period->id }}">{{ $period->period_start . ' s/d ' . $period->period_end }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <label class="form-label">Material Code</label>
                                <select type="text" class="form-select" id="material-code" wire:model="material_id" onchange="ChangeMaterial(this)" value="" required>
                                    <option value="">-Select Material-</option>
                                    @foreach ($materialModal as $mat)
                                        <option value="{{ $mat->id }}">{{ '[' . $mat->material_code . '] - ' . $mat->material_description }}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-3 mb-3">
                                <label class="form-label">Part NO</label>
                                <input type="text" id="part_no" class="form-control" readonly required>
                            </div>
                            <div class="col-3">
                                <label class="form-label">Material Description</label>
                                <input type="text" id="mat_desc" class="form-control" readonly required>
                            </div>
                            <div class="col-3">
                                <label class="form-label">UOM</label>
                                <input type="text" id="uom" class="form-control" readonly required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="">Note</label>
                                <textarea name="" id="" rows="3" class="form-control" wire:model="notes" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Save</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
<script>
    function ChangeMaterial(element) {
        var matcode = element.value;
        if (matcode) {
            document.getElementById('part_no').value = "<?php echo $materialModal[0]->part_no; ?>";
            document.getElementById('mat_desc').value = "<?php echo $materialModal[0]->material_description; ?>";
            document.getElementById('uom').value = "L";
        } else {
            document.getElementById('part_no').value = '';
            document.getElementById('mat_desc').value = '';
            document.getElementById('uom').value = '';
        }
    }
</script>
