<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Unit Of Measure
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
                                            <input type="text" class="form-control form-control-sm" aria-label="Code, Name" placeholder="Code, Name" wire:model.live="q">
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
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped table-bordered">
                                <thead>
                                    <tr class="text-nowrap">
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th class="text-center">UOM Code</th>
                                        <th class="text-center">UOM Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($uoms->isEmpty())
                                        {!! dataNotFond(4) !!}
                                    @else
                                        @foreach ($uoms as $idx => $activity)
                                            <tr class="text-nowrap">
                                                <td class="text-center">{{ $idx + 1 }}</td>
                                                <td>{{ $activity->uom_code }}</td>
                                                <td>{{ $activity->uom_name }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                            {{ $uoms->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
