<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6 d-flex justify-content-start">
                            <h2>Fuel Distribution</h2>
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
                                            <select wire:model='periodId' class="form-select form-select-sm">
                                                @foreach ($periods as $period)
                                                    <option value="{{ $period->id }}">
                                                        {{ 'Period ' . $period->period_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fa fa-search"></i> &nbsp; Cari &nbsp;
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="ms-2 d-inline-block">
                                <button id="btn-posting{{ -1 }}" class="btn btn-warning btn-sm"
                                    onclick="downloadExcel()">
                                    <i class="fas fa-file-excel"></i> &nbsp; Excel
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-bordered">
                                <thead>
                                    <tr class="text-nowrap">
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th class="text-center">Company </th>
                                        <th class="text-center">Stock Awal</th>
                                        <th class="text-center">Receipt</th>
                                        <th class="text-center">Transfer</th>
                                        <th class="text-center">Receipt Transfer</th>
                                        <th class="text-center">Issued</th>
                                        <th class="text-center">Adjust</th>
                                        <th class="text-center">Stock Akhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (empty($distributions))
                                        {!! dataNotFond(8) !!}
                                    @else
                                        @foreach ($distributions as $distribution)
                                            <tr class="text-nowrap">
                                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                                <td>{{ $distribution->company_name }}</td>
                                                <td class="text-end">
                                                    {{ number_format($distribution->opening_qty, '0', ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($distribution->rcv_qty, '0', ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($distribution->trf_qty, '0', ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($distribution->rcvTrf_qty, '0', ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($distribution->issued_qty, '0', ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($distribution->adjust_qty, '0', ',', '.') }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($distribution->closing_qty, '0', ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            async function downloadExcel() {
                console.log("buhahaaaa");
                const isConfirmed = await sweetPosting({
                    id: -1,
                    title: 'Download Report ? ',
                    textLoadong: '  loading'
                });
                if (isConfirmed) {
                    @this.call('report');
                }
            }
        </script>
    @endpush
</div>
