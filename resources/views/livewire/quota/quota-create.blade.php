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
                        <h5 class="modal-title" id="modal-largeLabel">{{ $id ? 'Edit' : 'Create' }} Quota</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="closeModal" id="closeModalID"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label for="" class="form-label required">Periode</label>
                                <select name="" id="" class="form-control" wire:model='periode' @if ($readOnly) readonly disabled @endif required>
                                    <option value="">-select Periode-</option>
                                    @foreach ($periods as $period)
                                        <option value="{{ $period->id }}">{{ $period->period_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="" class="form-label required">UOM</label>
                                <select name="" id="" class="form-control" wire:model="uom" @if ($readOnly) readonly disabled @endif required>
                                    <option value="">-Select UOM-</option>
                                    @foreach ($uoms as $uom)
                                        <option value="{{ $uom->id }}">{{ $uom->uom_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="" class="form-label required">Material</label>
                                <select name="" id="" class="form-control" wire:model.live="selectedMaterial" @if ($readOnly) readonly disabled @endif required>
                                    <option value="">-Select Material-</option>
                                    @foreach ($materials as $material)
                                        <option value="{{ $material->id }}">{{ $material->material_description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="" class="form-label required">Material Code
                                    <div wire:loading wire:target="selectedMaterial">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </label>
                                <input style=" background-color: #e9ecef; opacity: 1;" type="text" class="form-control" wire:model='code' placeholder="Material Code" readonly required>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="" class="form-label required">Part No
                                    <div wire:loading wire:target="selectedMaterial">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </label>
                                <input style=" background-color: #e9ecef; opacity: 1;" type="text" class="form-control" wire:model='partno' placeholder="Part No" readonly required>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="" class="form-label required">Material Mnemonic
                                    <div wire:loading wire:target="selectedMaterial">
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </label>
                                <input style=" background-color: #e9ecef; opacity: 1;" type="text" class="form-control" wire:model='mnemonic' placeholder="Part No" readonly required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="table-responsive">
                                        <table class="table table-vcenter card-table">
                                            <thead>
                                                <tr>
                                                    <th class="w-1">#</th>
                                                    <th>Company</th>
                                                    <th>Quota</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($companies as $company)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $company->company_name }}</td>
                                                        <td class="editable currency" contenteditable='true' data-company-id="{{ $company->id }}" onkeypress="allowNumbersOnly(event)"
                                                            onblur="updateQuota(this, '{{ $company->id }}')" style="background-color: #f9f9f9;">{{ $quotas[$company->id] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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
    function allowNumbersOnly(event) {
        const keyCode = event.which ? event.which : event.keyCode;
        if ((keyCode < 48 || keyCode > 57) && keyCode !== 46 && keyCode !== 8) {
            event.preventDefault();
        }
    }

    function formatCurrency(event) {
        let value = event.target.innerText;
        if (!value) return;
        value = value.replace(/[^0-9.]/g, '');
        event.target.innerText = value
    }

    function updateQuota(element, companyId) {
        let value = element.innerText.replace(/[^0-9.]/g, '');
        @this.call('updateQuota', companyId, value);
    }
</script>
