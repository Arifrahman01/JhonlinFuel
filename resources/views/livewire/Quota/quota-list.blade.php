<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title col-12">
                        <div class="col-6">
                            Qouta Request
                        </div>
                        <div class="col-6 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" wire:click="$dispatch('openModal')" data-bs-toggle="modal" data-bs-target="#modal-large"><i
                                class="fa fa-plus-circle"></i>&nbsp; Create</button>
                        </div>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Filter</h3>                         
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="search">
                                <div class="row">
                                    <div class="col-4">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control ">
                                    </div>
                                    <div class="col-4">
                                        <label for="email">Email</label>
                                        <input  type="text" class="form-control ">
                                    </div>
                                    <div class="col-4">
                                        <label for="role">Role</label>
                                        <select  class="form-control ">
                                            <option value="">- All Role -</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> &nbsp; Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th class="text-center" style="width: 5%">Action</th>
                                        <th>company</th>
                                        <th class="text-nowrap text-center">Request Number</th>
                                        <th class="text-center">Periode</th>
                                        <th class="text-nowrap text-center">Material Code</th>
                                        <th class="text-nowrap text-center">Part No</th>
                                        <th class="text-nowrap">Material Description</th>
                                        <th class="text-nowrap">UOM</th>
                                        <th class="text-nowrap">Quantity</th>
                                        <th class="">Note</th>
                                    </tr>
                                </thead>
                                <tbody class="text-nowrap">
                                    @if ($quotas->isEmpty())
                                        {!! dataNotFond(8) !!}
                                    @else
                                        @foreach ($quotas as $idx => $val)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td class="text-nowrap">
                                                    <a id="btn-delete{{ $val->id }}" title="Deleted Request" onclick="deleteItem({{ $val->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a> &nbsp;
                                                    <a title="Edit Request" wire:click="$dispatch('openModal', [{{ $val->id }}])" data-bs-toggle="modal" data-bs-target="#modal-large">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                                <td>{{ $val->company->company_name }}</td>
                                                <td>{{ $val->request_no }}</td>
                                                <td>{{ $val->period->period_start .' s/d '. $val->period->period_end }}</td>
                                                <td>{{ $val->details[0]->material_code }}</td>
                                                <td>{{ $val->details[0]->part_no }}</td>
                                                <td>{{ $val->details[0]->material_description }}</td>
                                                <td>{{ $val->details[0]->uom->uom_name }}</td>
                                                <td>{{ number_format($val->details[0]->qty, 2, ',', '.') }}</td>
                                                <td>{{ $val->notes}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer justify-content-between align-items-center">
                            {{ $quotas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @livewire('quota.modal-quota')
    @push('scripts')
        <script>
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
