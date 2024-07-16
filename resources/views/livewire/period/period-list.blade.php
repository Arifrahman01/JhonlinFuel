<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Period
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
                    <span wire:loading wire:target='periodSelected'
                        class="spinner-border spinner-border-sm text-primary" role="status"></span>
                    <div wire:loading.remove wire:target='periodSelected' class="card">
                        <div class="card-header">
                            <form wire:submit.prevent="search" class="w-100">
                                <div class="d-flex align-items-end">
                                    <div class="text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <select wire:model="selectedYear" class="form-select form-select-sm">
                                                {{-- <option value="">-Select Year-</option> --}}
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}">
                                                        {{ $year }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <select wire:model="selectedMonth" class="form-select form-select-sm">
                                                {{-- <option value="">-Select Month-</option> --}}
                                                @foreach ($months as $key => $month)
                                                    <option value="{{ $key }}">
                                                        {{ $month }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ms-2 text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                &nbsp; Cari &nbsp;
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="d-inline-block">
                                            <button type="button" class="btn btn-success" id="btn-posting0"
                                                onclick="openPeriod('{{ data_get($periodCompanies, '0.periods.0.period_id') }}')">
                                                &nbsp; Open &nbsp;
                                            </button>
                                        </div>
                                        <div class="ms-2 d-inline-block">
                                            <button type="button" class="btn btn-danger" id="btn-posting1"
                                                onclick="closePeriod('{{ data_get($periodCompanies, '0.periods.0.period_id') }}')">
                                                &nbsp; Close &nbsp;
                                            </button>
                                        </div>
                                    </div>
                                </div>


                            </form>
                        </div>
                        {{-- <div class="card-body">
                            <div class="datagrid">
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Period Name</div>
                                    <div class="datagrid-content">{{ $periodName }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">Start Date</div>
                                    <div class="datagrid-content">{{ $startDate }}</div>
                                </div>
                                <div class="datagrid-item">
                                    <div class="datagrid-title">End Date</div>
                                    <div class="datagrid-content">{{ $endDate }}</div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 6%;">
                                            <input class="form-check-input m-0 align-middle" type="checkbox"
                                                onchange="checkAll(this)" aria-label="Select All Company"
                                                id="main-checkbox">
                                        </th>
                                        <th class="text-center" style="width: 20%;">Period</th>
                                        <th class="text-center" style="width: 40%;">Company</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (empty($periodCompanies))
                                        {!! dataNotFond(2) !!}
                                    @else
                                        @foreach ($periodCompanies as $periodCompany)
                                            @php
                                                $status =
                                                    data_get($periodCompany, 'periods.0.pivot.status') ?? 'not-active';
                                                // $periodId_ = data_get($periodCompany, 'periods.0.period_id') ?? '';
                                                $class = '';

                                                switch ($status) {
                                                    case 'open':
                                                        $class = 'status-green';
                                                        break;
                                                    case 'close':
                                                        $class = 'status-danger';
                                                        break;
                                                    case 'not-active':
                                                        $class = 'status-warning';
                                                        break;
                                                    default:
                                                        $class = 'status-default';
                                                        break;
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    <input class="form-check-input m-0 align-middle detailCheckbox"
                                                        value="{{ $periodCompany->id }}" type="checkbox">
                                                </td>
                                                <td>{{ data_get($periodCompany, 'periods.0.period_name') ?? $months[$selectedMonth] . ' ' . $selectedYear }}
                                                </td>
                                                <td>{{ $periodCompany->company_name }}</td>
                                                <td>
                                                    <span class="status {{ $class }}">
                                                        {{ ucwords($status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('period.period-create')
    @push('scripts')
        <script>
            function checkAll(mainCheckbox) {
                const checkboxes = document.querySelectorAll('.detailCheckbox');

                checkboxes.forEach(checkbox => {
                    checkbox.checked = mainCheckbox.checked;
                });
            }

            async function openPeriod() {
                const mainCheckbox = document.getElementById('main-checkbox');
                const checkboxes = document.querySelectorAll('.detailCheckbox:checked');
                const companyIds = [];
                checkboxes.forEach(checkbox => {
                    companyIds.push(checkbox.value);
                });
                if (companyIds.length > 0) {
                    const isConfirmed = await sweetPosting({
                        id: 0,
                        title: 'Open period all data selected ? ',
                        textLoadong: '  Loading'
                    });
                    if (isConfirmed) {
                        @this.call('openPeriod', companyIds);
                        const checkboxes = document.querySelectorAll('.detailCheckbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        mainCheckbox.checked = false;
                    }
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Not have data selected",
                        icon: "error"
                    });
                }
            }

            async function closePeriod() {
                const mainCheckbox = document.getElementById('main-checkbox');
                const checkboxes = document.querySelectorAll('.detailCheckbox:checked');
                const companyIds = [];
                checkboxes.forEach(checkbox => {
                    companyIds.push(checkbox.value);
                });
                if (companyIds.length > 0) {
                    const isConfirmed = await sweetPosting({
                        id: 1,
                        title: 'Close period all data selected ? ',
                        textLoadong: '  Loading'
                    });
                    if (isConfirmed) {
                        @this.call('closePeriod', companyIds);
                        const checkboxes = document.querySelectorAll('.detailCheckbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        mainCheckbox.checked = false;
                    }
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Not have data selected",
                        icon: "error"
                    });
                }
            }
        </script>
    @endpush
</div>
