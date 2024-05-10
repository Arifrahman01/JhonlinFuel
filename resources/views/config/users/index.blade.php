@extends('layouts.index', ['title' => 'Company'])
@section('content')
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

                            <div class="row">
                                <div class="col-4 mb-2">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-4 mb-2">
                                    <label for="role">Role</label>
                                    <select name="" id="" class="form-control">
                                        <option value="">-All Role-</option>
                                        <option value="1">Admin</option>
                                        <option value="2">Guest</option>
                                    </select>
                                </div>
                                <div class="col-4">
                                    <br>
                                    <button class="btn btn-primary text-end"><i class="fa fa-search">  Filter</i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
