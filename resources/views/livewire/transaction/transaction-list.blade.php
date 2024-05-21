<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
                            <h1>Loader Issued</h1>  
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary me-2" wire:click="$dispatch('openEdit')" data-bs-toggle="modal" data-bs-target="#modal-large"><i class="fa fa-plus"></i>&nbsp;
                                Create</button>
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openUpload')" data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                    class="fa fa-file-excel"></i>&nbsp; Upload</button>
                        </div>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <form wire:submit.prevent="search">
                                <div class="d-flex">
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="date" class="form-control form-control-sm" id="start_date" wire:model="filter_date" aria-label="Start Date" placeholder="Start Date"
                                                value="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    {{-- <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            s/d
                                        </div>
                                    </div> --}}
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            {{-- <input type="date" class="form-control form-control-sm" id="end_date" aria-label="End Date" placeholder="End Date" value="{{ date('Y-m-d') }}"> --}}
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                &nbsp; Cari &nbsp;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table id="treegrid" role="treegrid" class="table table-sm table-striped" <colgroup>
                                <col id="treegrid-col1">
                                <col id="treegrid-col2">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 1%">Warehouse/Company</th>
                                        <th class="text-center" style="width: 10%">Date/Type Trans</th>
                                        <th class="text-center" style="width: 10%">Fuelman</th>
                                        <th class="text-center" style="width: 10%">Equipment No</th>
                                        <th class="text-center" style="width: 5%">Location</th>
                                        <th class="text-center" style="width: 5%">Department</th>
                                        <th class="text-center" style="width: 5%">Activity</th>
                                        <th class="text-center" style="width: 5%">Fuel & Oil Type</th>
                                        <th class="text-center" style="width: 10%">Litre Issued</th>
                                        <th class="text-center" style="width: 5%">Statistic Type</th>
                                        <th class="text-center" style="width: 10%">Meter Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($transactions->isEmpty())
                                    <tr>
                                        <td colspan="11" class="text-left">&nbsp;<i class="fa fa-info-circle"> &nbsp;&nbsp;</i> Data not found</td>
                                    </tr>
                                    @else
                                        @foreach ($transactions as $idx => $trans)
                                            <tr role="row" aria-level="1" aria-posinset="1" aria-setsize="2" aria-expanded="false">
                                                <td role="gridcell">
                                                    <a id="btn-posting{{ $idx + 1 }}" title="Posting Transaksi"
                                                        onclick="postingItem({{ $idx + 1 }}, '{{ $trans['summary']->fuel_warehouse }}', '{{ $trans['summary']->trans_date }}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                                            <path
                                                                d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                                                        </svg>
                                                    </a> &nbsp;
                                                    {{ $trans['summary']->fuel_warehouse }}
                                                </td>
                                                <td colspan="6" class="text-nowrap" role="gridcell">{{ $trans['summary']->trans_date }}</td>
                                                <td colspan="2" style="text-align: right">{{ number_format($trans['summary']->total_qty, 2, '.', ',') }}</td>
                                                <td colspan="2"></td>
                                            </tr>
                                            @foreach ($trans['details'] as $indx => $detail)
                                                <tr role="row" aria-level="2" aria-posinset="1" aria-setsize="15" class="hidden text-nowrap">
                                                    <td role="gridcell">
                                                        <a id="btn-delete{{ $detail->id ?? '' }}" title="Deleted Transaction" onclick="deleteItem({{ $detail->id }})">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                        <a title="Edit Transaction" wire:click="$dispatch('openEdit', [{{ $detail->id ?? '' }}])" data-bs-toggle="modal"
                                                            data-bs-target="#modal-large">
                                                            <i class="fas fa-edit"></i>
                                                        </a>

                                                        @if (!$detail->status_error == null) 
                                                            <i href="#" class="fa fa-info-circle" style="color: red" title="{{ $detail->status_error }}">
                                                            </i>
                                                        @else
                                                            &nbsp;
                                                        @endif


                                                        {{ $detail->company_code }}
                                                    </td>
                                                    <td role="gridcell">{{ $detail->trans_type }}</td>
                                                    <td role="gridcell">{{ $detail->fuelman }}</td>
                                                    <td role="gridcell">{{ $detail->equipment_no }}</td>
                                                    <td role="gridcell">{{ $detail->location }}</td>
                                                    <td role="gridcell">{{ $detail->department }}</td>
                                                    <td role="gridcell">{{ $detail->activity }}</td>
                                                    <td role="gridcell">{{ $detail->fuel_type }}</td>
                                                    <td role="gridcell" style="text-align: right">{{ number_format($detail->qty, 2, '.', ',') }}</td>
                                                    <td role="gridcell">{{ $detail->statistic_type }}</td>
                                                    <td role="gridcell" style="text-align: right">{{ number_format($detail->meter_value, 2, '.', ',') }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                            {{-- {{ $transactions->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('transaction.modal-transaction')
    @push('scripts')
        <script src="./dist/js/custom.js"></script>
        <script>
            async function deleteItem(id) {
                const isConfirmed = await sweetDeleted({
                    id: id
                });
                if (isConfirmed) {
                    @this.call('delete', id);
                }
            }
            async function postingItem(id, warehouse, date) {
                const isConfirmed = await sweetPosting({
                    id: id
                });
                if (isConfirmed) {
                    @this.call('posting', id, warehouse, date);
                }
            }

            function setEndDateMax() {
                var startDate = document.getElementById("start_date").value;
                var endDateField = document.getElementById("end_date");
                if (!startDate) {
                    endDateField.removeAttribute("max");
                    return;
                }
                var maxDate = new Date(startDate);
                maxDate.setDate(maxDate.getDate() + 30);
                var maxDateString = maxDate.toISOString().split('T')[0];
                endDateField.setAttribute("max", maxDateString);
                if (endDateField.value > maxDateString) {
                    endDateField.value = maxDateString;
                }
            }
        </script>
    @endpush
</div>
