<div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Management User
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
                        <div class="card-header">
                            <h3 class="card-title">Filter</h3>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="searching">
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label for="name">Name</label>
                                        <input wire:model="name" type="text" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="email">Email</label>
                                        <input wire:model="email" type="text" class="form-control">
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="role">Role</label>
                                        <select wire:model="role" class="form-control">
                                            <option value="">- All Role -</option>
                                            <option value="admin">Admin</option>
                                            <option value="guest">Guest</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Filter</button>
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($data->isEmpty())
                                        {!! dataNotFond(8) !!}
                                    @else
                                        @foreach ($data as $idx => $user)
                                            <tr>
                                                <td>{{ $idx + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->role_id == 1 ? 'Administrator' : 'Guest' }}</td>
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
