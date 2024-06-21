<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Period
                        </div>
                        @can('create-master-company')
                            <div class="col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" wire:click="$dispatch('openCreate')"
                                    data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                        class="fa fa-plus-circle"></i>&nbsp;
                                    Create</button>
                            </div>
                        @endcan
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
                        {{-- <div class="card-header">
                            <form wire:submit.prevent="search">
                                <div class="d-flex">
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <input type="text" class="form-control form-control-sm"
                                                aria-label="Period Name" placeholder="Period Name" wire:model="q">
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
                        </div> --}}
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-bordered" id="period-table">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 6%">Action</th>
                                        <th class="text-center">Period Name</th>
                                        <th class="text-center">Start Date</th>
                                        <th class="text-center">End Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($periods->isEmpty())
                                        {!! dataNotFond(2) !!}
                                    @else
                                        @foreach ($periods as $period)
                                            <tr class="{{ $loop->index == 0 ? 'selected' : '' }}" wire:ignore.self
                                                onclick="changeSelected('{{ $period->id }}', this)">
                                                <td class="text-center">
                                                    @can('delete-master-period')
                                                        @if (!$period->hasDataById())
                                                            <a id="btn-delete{{ $period->id }}" title="Delete Period"
                                                                onclick="deleteItem({{ $period->id }})">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a> &nbsp;
                                                        @endif
                                                    @endcan
                                                    @can('edit-master-period')
                                                        <a title="Edit Period"
                                                            wire:click="$dispatch('openCreate', [{{ $period->id }}])"
                                                            data-bs-toggle="modal" data-bs-target="#modal-large">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                                <td>{{ $period->period_name }}</td>
                                                <td>{{ $period->start_date }}</td>
                                                <td>{{ $period->end_date }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        {{-- </div> --}}

                        <div class="card-footer justify-content-between align-items-center">
                            {{ $periods->links() }}
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <span wire:loading wire:target='periodSelected'
                        class="spinner-border spinner-border-sm text-primary" role="status"></span>
                    <div wire:loading.remove wire:target='periodSelected' class="card">
                        <div class="card-header">
                            <form wire:submit.prevent="search">
                                <div class="d-flex">
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <select wire:model="selectedYear" class="form-select form-select-sm">
                                                <option value="">Current Year</option>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}">
                                                        {{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            <select wire:model="selectedMonth" class="form-select form-select-sm">
                                                <option value="">Current Month</option>
                                                @foreach ($months as $month)
                                                    <option value="{{ $month }}">
                                                        {{ $month }}</option>
                                                @endforeach
                                            </select>
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
                            <table class="table table-vcenter card-table table-bordered"ƒ>
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 6%;">Action</th>
                                        <th class="text-center" style="width: 60%;">Company</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (empty($periodCompanies))
                                        {!! dataNotFond(2) !!}
                                    @else
                                        @foreach ($periodCompanies as $periodCompany)
                                            @php
                                                $status = data_get($periodCompany, 'pivot.status') ?? '';
                                                $periodId_ = data_get($periodCompany, 'pivot.period_id') ?? '';
                                                $companyId_ = data_get($periodCompany, 'pivot.company_id') ?? '';
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
                                                        type="checkbox">
                                                    {{-- @if ($class == 'status-green')
                                                        @can('close-period')
                                                            <button class="btn btn-sm btn-danger"
                                                                onclick="closePeriod('{{ $periodId_ }}', '{{ $companyId_ }}')">
                                                                Close
                                                            </button>
                                                        @endcan
                                                    @else
                                                        @can('open-period')
                                                            <button class="btn btn-sm btn-warning"
                                                                onclick="openPeriod('{{ $periodId_ }}', '{{ $companyId_ }}')">
                                                                Open
                                                            </button>
                                                        @endcan
                                                    @endif --}}
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
            async function openPeriod() {
                const isConfirmed = await sweetDeleted({
                    id: id
                });
                if (isConfirmed) {
                    @this.call('delete', id);
                }
            }
            async function changeSelected(id, el) {
                @this.call('periodSelected', id);

                const table = document.getElementById('period-table');
                const rows = table.getElementsByTagName('tr');

                for (let j = 1; j < rows.length; j++) {
                    rows[j].classList.remove('selected');
                }

                el.classList.add('selected');
            }

            async function openPeriod(periodId, companyId) {
                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: 'Open this period!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, open this period!'
                });
                if (result.isConfirmed) {
                    @this.call('openPeriod', periodId, companyId);
                }
            }

            async function closePeriod(periodId, companyId) {
                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: 'Close this period!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, close this period!'
                });
                if (result.isConfirmed) {
                    @this.call('closePeriod', periodId, companyId);
                }
            }

            async function deleteItem(id) {
                const isConfirmed = await sweetDeleted({
                    id: id
                });
                if (isConfirmed) {
                    @this.call('delete', id);
                }
            }
        </script>
    @endpush
</div>
