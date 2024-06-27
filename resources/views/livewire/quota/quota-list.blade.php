<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Qouta
                        </div>
                        @can('create-master-plant')
                            <div class="col-6 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" wire:click="$dispatch('openCreate', [{{ $selectedPeriod }}])"
                                data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                    class="fa fa-edit"></i>&nbsp;
                                Edit
                            </button> &nbsp;
                                <button type="button" class="btn btn-primary" wire:click="$dispatch('openCreate')"
                                    data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                        class="fa fa-plus-circle"></i>&nbsp;
                                    Create
                                </button>
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
                        <div class="card-header">
                            <form wire:submit.prevent="search">
                                <div class="d-flex">
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                            
                                        </div>
                                    </div>
                                    <div class="ms-auto text-muted">
                                        <div class="ms-2 d-inline-block">
                                                <select name="" id="" class="form-control form-control-sm" wire:model.live='selectedPeriod'>
                                                    <option value="">&nbsp;&nbsp;&nbsp;&nbsp; - Select Period - &nbsp;&nbsp;&nbsp;&nbsp;</option>
                                                    @foreach ($periods as $period)
                                                        <option value="{{ $period->id }}">{{ $period->period_name }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 6%">no</th>
                                        <th class="text-center">Company</th>
                                        <th class="text-center">Qouta</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($quotas->isEmpty())
                                        {!! dataNotFond(2) !!}
                                    @else
                                        @foreach ($quotas as $quota)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration  }}
                                                </td>
                                                <td>
                                                    {{ $quota->company->company_name }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($quota->qty,'0',',','.') }}
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                            {{-- {{ $quotas->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('quota.quota-create')
    @push('scripts')
        <script>
            document.addEventListener('livewire:init', function() {
                Livewire.on('logData', data => {
                    console.log(data);
                });
            });
        </script>
    @endpush
</div>
