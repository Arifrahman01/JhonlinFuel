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
                                <input class="form-control"  accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" onchange="ChangeTess(this)" id="fileLoader" name="fileLoader" wire:model="fileLoader" type="file" required>
                                <input class="form-control" id="tess" wire:model="tess" type="text" value="123">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal" wire:click="closeModal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; Upload</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
<script>
    function ChangeTess()
    {
        document.getElementById('tess').value = "te123ss";
    }
</script>
