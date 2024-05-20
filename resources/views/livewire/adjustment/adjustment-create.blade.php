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
                <form>
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-largeLabel">
                            Tambah Adjustment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            wire:click="closeModal" id="closeModalID"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row row-cards mb-3">
                            <div class="col-4 mb-3">
                                <label class="form-label required">Plant</label>
                                <select wire:model.live="selectedPlant" class="form-select" required >
                                    <option value="">-Select Plant-</option>
                                    @foreach ($plants as $plant)
                                        <option value="{{ $plant->id }}">
                                            {{ $plant->plant_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label required">Sloc</label>
                                @if ($isLoadingSloc)
                                    <div class="input-icon mb-3">
                                        <input type="text" value="" class="form-control" placeholder="Loading…"
                                            disabled>
                                        <span class="input-icon-addon">
                                            <div class="spinner-border spinner-border-sm text-muted" role="status">
                                            </div>
                                        </span>
                                    </div>
                                @else
                                    <select wire:model.live="selectedSloc" wire:key="{{ $selectedPlant }}"
                                        class="form-select" required>
                                        <option value="0">-Select Sloc-</option>
                                        @foreach ($slocs as $sloc)
                                            <option value="{{ $sloc->id }}">
                                                {{ $sloc->sloc_name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <div class="col-4 mb-3">
                                <label class="form-label required">Adjust Qty</label>
                                <input type="number" wire:model.live="sohAdjustment" class="form-control" required
                                    @if ($sohAdjustmentReadOnly) readonly @endif>

                            </div>
                            <div class="col-6">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" data-bs-toggle="autosize" placeholder="Notes" wire:model="notes"></textarea>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Original Qty</label>
                                <div>{{ $soh != '-' ? number_format($soh, 0, ',', '.') : $soh }}</div>

                            </div>
                            <div class="col-2 mb-3">
                                <label class="form-label">Qty After</label>
                                <div>{{ $sohAfter != '-' ? number_format($sohAfter, 0, ',', '.') : $sohAfter }}
                                </div>

                            </div>



                            <div class="col-12">
                                <button type="button" class="btn btn-primary w-100" wire:click="addData">Add</button>
                            </div>

                        </div>
                        <div class="card">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="w-1"></th>
                                            <th>Plant</th>
                                            <th>Sloc</th>
                                            <th>Origin Qty</th>
                                            <th>Adjust Qty</th>
                                            <th>Qty After</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datas as $data)
                                            <tr>
                                                <td>
                                                    <a title="Delete Item" wire:click="deleteItem({{ $loop->index }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                                <td>{{ $data->plant }}</td>
                                                <td>{{ $data->sloc }}</td>
                                                <td style="text-align: end">
                                                    {{ number_format($data->soh_before, 0, ',', '.') }}</td>
                                                <td style="text-align: end">
                                                    {{ number_format($data->soh_adjust, 0, ',', '.') }}</td>
                                                <td style="text-align: end">
                                                    {{ number_format($data->soh_after, 0, ',', '.') }}</td>
                                                <td>{{ $data->notes }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal"
                            wire:click="closeModal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="storeAdjustment"><i
                                class="fa fa-save"></i>&nbsp; Save</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>
