<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Adjustment
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openModal')"
                                data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                    class="fa fa-plus-circle"></i>&nbsp;
                                Create</button>
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
                                            <input type="text" id="search" class="form-control form-control-sm"
                                                aria-label="Adjustment No" placeholder="Adjustment No"
                                                wire:model="adjNo">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <select wire:model.live="c" id="company" class="form-select form-select-sm">
                                                <option value="">-Select Company-</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->company_code }}">
                                                        {{ $company->company_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="date" class="form-control form-control-sm" id="start_date" wire:model="start_date" aria-label="Start Date"
                                                placeholder="Start Date" value="{{ $start_date }}">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            s/d
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="date" class="form-control form-control-sm" id="end_date" wire:model="end_date" aria-label="End Date" placeholder="End Date"
                                                value="{{ $end_date }}">
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                &nbsp; <i class="fa fa-search"></i>   &nbsp;  Cari &nbsp;
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </form>
                            <div class="ms-2 d-inline-block">
                                <button id="btn-posting{{ -1 }}" class="btn btn-warning btn-sm" onclick="downloadExcel({{ -1 }})">
                                    <i class="fas fa-file-excel"></i> &nbsp; Excel
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="treegrid" class="table table-vcenter card-table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">#</th>
                                        {{-- <th class="text-center" style="width: 5%">Action</th> --}}
                                        <th class="text-center">Company</th>
                                        <th class="text-center">Adj. Date</th>
                                        <th colspan="4">Adj. No</th>
                                        {{-- <th class="text-center">Origin Qty</th>
                                        <th class="text-center">Adjust Qty</th>
                                        <th class="text-center">Qty After</th>
                                        <th class="text-center">Notes</th> --}}
                                        {{-- <th class="w-1"></th> --}}
                                    </tr>
                                    <tr>
                                        <th class="text-center" style="width: 5%"></th>
                                        {{-- <th class="text-center" style="width: 5%">Action</th> --}}
                                        <th class="text-center">Plant</th>
                                        <th class="text-center">Sloc</th>
                                        <th class="text-center">Origin Qty</th>
                                        <th class="text-center">Adjust Qty</th>
                                        <th class="text-center">Qty After</th>
                                        <th class="text-center">Notes</th>
                                        {{-- <th class="w-1"></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($adjusts->isEmpty())
                                        {!! dataNotFond(6) !!}
                                    @else
                                        @foreach ($adjusts as $adjust)
                                            <tr role="row" aria-level="1" aria-posinset="1" aria-setsize="1"
                                                aria-expanded="false">
                                                <td>{{ ($adjusts->currentPage() - 1) * $adjusts->perPage() + $loop->index + 1 }}
                                                </td>
                                                <td>{{ data_get($adjust, 'company.company_name') }}</td>
                                                <td>{{ $adjust->adjustment_date }}</td>
                                                <td colspan="4">{{ $adjust->adjustment_no }}</td>
                                            </tr>
                                            @foreach ($adjust->details as $detail)
                                                <tr role="row" aria-level="2" aria-posinset="2" aria-setsize="3"
                                                    class="hidden">
                                                    <td></td>
                                                    <td>{{ $detail->plant->plant_name }}</td>
                                                    <td>{{ $detail->sloc->sloc_name }}</td>
                                                    <td style="text-align: right">
                                                        {{ number_format($detail->origin_qty, 0, ',', '.') }}</td>
                                                    <td style="text-align: right">
                                                        {{ number_format($detail->adjust_qty, 0, ',', '.') }}</td>
                                                    <td style="text-align: right">
                                                        {{ number_format(toNumber($detail->origin_qty) + toNumber($detail->adjust_qty), 0, ',', '.') }}
                                                    </td>
                                                    <td>{{ $detail->notes }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $adjusts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('adjustment.adjustment-create')
    @push('scripts')
        <script>
            document.addEventListener('livewire:init', function() {
                Livewire.on('logData', data => {
                    console.log(data);
                });
            });

            async function downloadExcel(id) {
                const isConfirmed = await sweetPosting({
                    id: id,
                    title: 'Download Report ? ',
                    textLoadong: '  loading'
                });
                if (isConfirmed) {
                    const search = document.getElementById('search').value;
                    const company = document.getElementById("company").value;
                    const startDate = document.getElementById("start_date").value;
                    const endDate = document.getElementById("end_date").value;
                    @this.call('report', search, company, startDate, endDate);
                }
            }
        </script>
    @endpush
</div>
