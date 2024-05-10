@extends('layouts.index', ['title' => 'Company'])
@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Example Page
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
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Title</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Maryjo Lebarree</td>
                                        <td class="text-muted">
                                            Civil Engineer, Product Management
                                        </td>
                                        <td class="text-muted"><a href="#" class="text-reset">mlebarree5@unc.edu</a>
                                        </td>
                                        <td class="text-muted">
                                            User
                                        </td>
                                        <td>
                                            <a href="#">Edit</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Egan Poetz</td>
                                        <td class="text-muted">
                                            Research Nurse, Engineering
                                        </td>
                                        <td class="text-muted"><a href="#" class="text-reset">epoetz6@free.fr</a>
                                        </td>
                                        <td class="text-muted">
                                            Admin
                                        </td>
                                        <td>
                                            <a href="#">Edit</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Kellie Skingley</td>
                                        <td class="text-muted">
                                            Teacher, Services
                                        </td>
                                        <td class="text-muted"><a href="#" class="text-reset">kskingley7@columbia.edu</a></td>
                                        <td class="text-muted">
                                            User
                                        </td>
                                        <td>
                                            <a href="#">Edit</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Christabel Charlwood</td>
                                        <td class="text-muted">
                                            Tax Accountant, Engineering
                                        </td>
                                        <td class="text-muted"><a href="#" class="text-reset">ccharlwood8@nifty.com</a></td>
                                        <td class="text-muted">
                                            Owner
                                        </td>
                                        <td>
                                            <a href="#">Edit</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Haskel Shelper</td>
                                        <td class="text-muted">
                                            Staff Scientist, Legal
                                        </td>
                                        <td class="text-muted"><a href="#" class="text-reset">hshelper9@woothemes.com</a></td>
                                        <td class="text-muted">
                                            Admin
                                        </td>
                                        <td>
                                            <a href="#">Edit</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    {{-- <div class="card"> --}}
                    <div class="card-header">
                        <div class="table-wrap">
                            <table id="treegrid" role="treegrid" aria-label="Inbox" class="table table-sm table-striped" <colgroup>
                                <col id="treegrid-col1">
                                <col id="treegrid-col2">
                                <col id="treegrid-col3">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Liter</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr role="row" aria-level="1" aria-posinset="1" aria-setsize="1" aria-expanded="true">
                                        <td role="gridcell">PT. Jhonlin Group</td>
                                        <td role="gridcell"><span class="badge bg-primary">100%</span></td>
                                        <td role="gridcell">2.123.00</td>
                                    </tr>

                                    <tr role="row" aria-level="2" aria-posinset="2" aria-setsize="3" aria-expanded="false">
                                        <td role="gridcell">PT. Jhonlin Batubara</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">1.234.456</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1" class="hidden">
                                        <td role="gridcell">Gudang 1</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">500.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1" class="hidden">
                                        <td role="gridcell">Gudang 1</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">500.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1" class="hidden">
                                        <td role="gridcell">Gudang 2</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">234.456</td>
                                    </tr>


                                    <tr role="row" aria-level="2" aria-posinset="3" aria-setsize="3" aria-expanded="false">
                                        <td role="gridcell">PT. Jhonlin Marine Trans</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">1.000.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1" aria-expanded="false" class="hidden">
                                        <td role="gridcell">Gudang 1</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">300.000</td>
                                    </tr>
                                    <tr role="row" aria-level="4" aria-posinset="1" aria-setsize="2" class="hidden">
                                        <td role="gridcell">Kapal 1</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">150.000</td>
                                    </tr>
                                    <tr role="row" aria-level="4" aria-posinset="2" aria-setsize="2" class="hidden">
                                        <td role="gridcell">Kapal 2</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">150.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1" aria-expanded="false" class="hidden">
                                        <td role="gridcell">Gudang 2</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">300.000</td>
                                    </tr>
                                    <tr role="row" aria-level="4" aria-posinset="1" aria-setsize="2" class="hidden">
                                        <td role="gridcell">Kapal 3</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">150.000</td>
                                    </tr>
                                    <tr role="row" aria-level="4" aria-posinset="2" aria-setsize="2" class="hidden">
                                        <td role="gridcell">Kapal 4</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">150.000</td>
                                    </tr>
                                    <tr role="row" aria-level="3" aria-posinset="1" aria-setsize="1" class="hidden">
                                        <td role="gridcell">Gudang 3</td>
                                        <td role="gridcell"><span class="badge bg-primary">30%</span></td>
                                        <td role="gridcell">300.000</td>
                                    </tr>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>
        <script src="./dist/js/custom.js"></script>
    @endsection
