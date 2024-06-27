<div class="collapse navbar-collapse" id="navbar-menu">
    <div class="navbar">
        <div class="container-xl">
            <ul class="navbar-nav">
                @can('view-dashboard')
                    <li class="nav-item {{ request()->is('/') || request()->is('/*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('home') }}">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                    <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Dashboard
                            </span>
                        </a>
                    </li>
                @endcan
                @canany(['view-transaksi-receipt-po', 'view-transaksi-transfer', 'view-transaksi-receipt-transfer',
                    'view-transaksi-issue', 'view-transaksi-adjustment'])
                    <li
                        class="nav-item {{ request()->is('receipt') || request()->is('adjustment') || request()->is('issue') || request()->is('receipt-transfer') || request()->is('transfer') ? 'active' : '' }} dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                    <path d="M12 12l8 -4.5" />
                                    <path d="M12 12l0 9" />
                                    <path d="M12 12l-8 -4.5" />
                                    <path d="M16 5.25l-8 4.5" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Data Transaksi
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    @can('view-transaksi-receipt-po')
                                        <a class="dropdown-item {{ request()->is('receipt') ? 'active' : '' }}"
                                            href="{{ route('receipt.index') }}">

                                            Receipt PO
                                        </a>
                                    @endcan
                                    @can('view-transaksi-transfer')
                                        <a class="dropdown-item {{ request()->is('transfer') ? 'active' : '' }}"
                                            href="{{ route('transfer.index') }}">
                                            Transfer
                                        </a>
                                    @endcan
                                    @can('view-transaksi-receipt-transfer')
                                        <a class="dropdown-item {{ request()->is('receipt-transfer') ? 'active' : '' }}"
                                            href="{{ route('receipt-transfer.index') }}">

                                            Receipt Transfer
                                        </a>
                                    @endcan
                                    @can('view-transaksi-issue')
                                        <a class="dropdown-item {{ request()->is('issue') ? 'active' : '' }}"
                                            href="{{ route('issue.index') }}">

                                            Issue
                                        </a>
                                    @endcan
                                    @can('view-transaksi-adjustment')
                                        <a class="dropdown-item {{ request()->is('adjustment') ? 'active' : '' }}"
                                            href="{{ route('adjustment.index') }}">

                                            Adjustment
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </li>
                @endcanany
                @canany(['view-loader-receipt-po', 'create-loader-receipt-po', 'edit-loader-receipt-po',
                    'delete-loader-receipt-po', 'posting-loader-receipt-po', 'view-loader-transfer',
                    'create-loader-transfer', 'edit-loader-transfer', 'delete-loader-transfer', 'posting-loader-transfer',
                    'view-loader-receipt-transfer', 'create-loader-receipt-transfer', 'edit-loader-receipt-transfer',
                    'delete-loader-receipt-transfer', 'posting-loader-receipt-transfer', 'view-loader-issue',
                    'create-loader-issue', 'edit-loader-issue', 'delete-loader-issue', 'posting-loader-issue'])

                    <li class="nav-item {{ request()->is('loader/*') ? 'active' : '' }} dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/package -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                    <path d="M12 12l8 -4.5" />
                                    <path d="M12 12l0 9" />
                                    <path d="M12 12l-8 -4.5" />
                                    <path d="M16 5.25l-8 4.5" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Loader
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    @canany(['view-loader-receipt-po', 'create-loader-receipt-po', 'edit-loader-receipt-po',
                                        'delete-loader-receipt-po', 'posting-loader-receipt-po'])
                                        <a class="dropdown-item {{ request()->is('loader/receipt') ? 'active' : '' }}"
                                            href="{{ route('received.loader') }}">
                                            Receipt PO
                                        </a>
                                    @endcan
                                    @canany(['view-loader-transfer', 'create-loader-transfer', 'edit-loader-transfer',
                                        'delete-loader-transfer', 'posting-loader-transfer'])
                                        <a class="dropdown-item {{ request()->is('loader/transfer') ? 'active' : '' }}"
                                            href="{{ route('transfer.loader') }}">

                                            Transfer
                                        </a>
                                    @endcan
                                    @canany(['view-loader-receipt-transfer', 'create-loader-receipt-transfer',
                                        'edit-loader-receipt-transfer', 'delete-loader-receipt-transfer',
                                        'posting-loader-receipt-transfer'])
                                        <a class="dropdown-item {{ request()->is('loader/receipt-transfer') ? 'active' : '' }}"
                                            href="{{ route('receipt-transfer.loader') }}">

                                            Receipt Transfer
                                        </a>
                                    @endcan
                                    @canany(['view-loader-issue', 'create-loader-issue', 'edit-loader-issue',
                                        'delete-loader-issue', 'posting-loader-issue'])
                                        <a class="dropdown-item {{ request()->is('loader/issue') ? 'active' : '' }}"
                                            href="{{ route('issue.loader') }}">

                                            Issue
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </li>
                @endcanany
                @canany(['view-master-company', 'create-master-company', 'edit-master-company', 'delete-master-company',
                    'view-master-plant', 'create-master-plant', 'edit-master-plant', 'delete-master-plant',
                    'view-master-warehouse', 'create-master-warehouse', 'edit-master-warehouse', 'delete-master-warehouse',
                    'view-master-fuelman', 'create-master-fuelman', 'edit-master-fuelman', 'delete-master-fuelman',
                    'view-master-department', 'create-master-department', 'edit-master-department',
                    'delete-master-department', 'view-master-activity', 'create-master-activity', 'edit-master-activity',
                    'delete-master-activity', 'view-master-equipment', 'create-master-equipment', 'edit-master-equipment',
                    'delete-master-equipment', 'view-master-material', 'create-master-material', 'edit-master-material',
                    'delete-master-material', 'view-master-uom', 'create-master-uom', 'edit-master-uom',
                    'delete-master-uom', 'view-master-period', 'create-master-period', 'edit-master-period',
                    'delete-master-period'])
                    <li
                        class="nav-item dropdown {{ request()->is('company') || request()->is('plant') || request()->is('warehouse') || request()->is('fuelman') || request()->is('department') || request()->is('equipment') || request()->is('material') || request()->is('uom') || request()->is('period')  || request()->is('quota')? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span
                                class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path
                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Master
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    @canany(['view-master-company', 'create-master-company', 'edit-master-company',
                                        'delete-master-company'])
                                        <a class="dropdown-item {{ request()->is('company') ? 'active' : '' }}"
                                            href="{{ route('company.index') }}">
                                            Company
                                        </a>
                                    @endcanany
                                    @canany(['view-master-plant', 'create-master-plant', 'edit-master-plant',
                                        'delete-master-plant'])
                                        <a class="dropdown-item {{ request()->is('plant') ? 'active' : '' }}"
                                            href="{{ route('plant.index') }}">
                                            Plant
                                        </a>
                                    @endcanany
                                    @canany(['view-master-warehouse', 'create-master-warehouse', 'edit-master-warehouse',
                                        'delete-master-warehouse'])
                                        <a class="dropdown-item {{ request()->is('warehouse') ? 'active' : '' }}"
                                            href="{{ route('warehouse.index') }}">
                                            Warehouse
                                        </a>
                                    @endcanany
                                    @canany(['view-master-fuelman', 'create-master-fuelman', 'edit-master-fuelman',
                                        'delete-master-fuelman'])
                                        <a class="dropdown-item {{ request()->is('fuelman') ? 'active' : '' }}"
                                            href="{{ route('fuelman.index') }}">
                                            Fuelman
                                        </a>
                                    @endcanany
                                    @canany(['view-master-department', 'create-master-department', 'edit-master-department',
                                        'delete-master-department'])
                                        <a class="dropdown-item {{ request()->is('department') ? 'active' : '' }}"
                                            href="{{ route('department.index') }}">
                                            Department
                                        </a>
                                    @endcanany
                                    @canany(['view-master-activity', 'create-master-activity', 'edit-master-activity',
                                        'delete-master-activity'])
                                        <a class="dropdown-item {{ request()->is('activity') ? 'active' : '' }}"
                                            href="{{ route('activity.index') }}">
                                            Activity
                                        </a>
                                    @endcanany
                                    @canany(['view-master-equipment', 'create-master-equipment', 'edit-master-equipment',
                                        'delete-master-equipment'])
                                        <a class="dropdown-item {{ request()->is('equipment') ? 'active' : '' }}"
                                            href="{{ route('equipment.index') }}">
                                            Equipment
                                        </a>
                                    @endcanany
                                    @canany(['view-master-material', 'create-master-material', 'edit-master-material',
                                        'delete-master-material'])
                                        <a class="dropdown-item {{ request()->is('material') ? 'active' : '' }}"
                                            href="{{ route('material.index') }}">
                                            Material
                                        </a>
                                    @endcanany
                                    @canany(['view-master-uom', 'create-master-uom', 'edit-master-uom',
                                        'delete-master-uom'])
                                        <a class="dropdown-item {{ request()->is('uom') ? 'active' : '' }}"
                                            href="{{ route('uom.index') }}">
                                            Unit Of Measure
                                        </a>
                                    @endcanany
                                    @canany(['view-master-period', 'create-master-period', 'edit-master-period',
                                        'delete-master-period'])
                                        <a class="dropdown-item {{ request()->is('period') ? 'active' : '' }}"
                                            href="{{ route('period.index') }}">
                                            Period
                                        </a>
                                    @endcanany
                                    <a class="dropdown-item {{ request()->is('quota') ? 'active' : '' }}"
                                        href="{{ route('quota.index') }}">
                                        Quota
                                    </a>
                                </div>
                            </div>
                    </li>
                @endcanany
                @canany(['view-report-stock-overview', 'view-report-fuel-consumption'])
                    <li
                        class="nav-item {{ request()->is('stock-overview') || request()->is('consumption') ? 'active' : '' }} dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M15 15l3.35 3.35" />
                                    <path d="M9 15l-3.35 3.35" />
                                    <path d="M5.65 5.65l3.35 3.35" />
                                    <path d="M18.35 5.65l-3.35 3.35" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                Report
                            </span>
                        </a>

                        <div class="dropdown-menu">
                            @can('view-report-stock-overview')
                                <a class="dropdown-item {{ request()->is('stock-overview') ? 'active' : '' }}"
                                    href="{{ route('stock.index') }}" rel="noopener">
                                    Stock Overview
                                </a>
                            @endcan
                            @can('view-report-fuel-consumption')
                                <a class="dropdown-item {{ request()->is('consumption') ? 'active' : '' }}"
                                    href="{{ route('consumption.index') }}" rel="noopener">
                                    Fuel Consumption
                                </a>
                            @endcan
                            {{-- <a class="dropdown-item" href="./changelog.html">
                            Report 2
                        </a> --}}
                        </div>
                    </li>
                @endcanany
                @canany(['view-user', 'create-user', 'edit-user', 'delete-user', 'view-role', 'create-role',
                    'edit-role', 'delete-role'])
                    <li class="nav-item dropdown {{ request()->is('users') || request()->is('role') ? 'active' : '' }}">
                        <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown"
                            data-bs-auto-close="outside" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-friends">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M7 5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M5 22v-5l-1 -1v-4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4l-1 1v5" />
                                    <path d="M17 5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M15 22v-4h-2l2 -6a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1l2 6h-2v4" />
                                </svg>
                            </span>
                            <span class="nav-link-title">
                                User Management
                            </span>
                        </a>
                        <div class="dropdown-menu">
                            @canany(['view-user', 'create-user', 'edit-user', 'delete-user'])
                                <a class="dropdown-item {{ request()->is('users') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}" rel="noopener">
                                    User
                                </a>
                            @endcan
                            @canany(['view-role', 'create-role', 'edit-role', 'delete-role'])
                                <a class="dropdown-item {{ request()->is('role') ? 'active' : '' }}"
                                    href="{{ route('role.index') }}">
                                    Role
                                </a>
                            @endcan
                        </div>
                    </li>
                @endcan
            </ul>
            {{-- <div class="my-2 my-md-0 flex-grow-1 flex-md-grow-0 order-first order-md-last">
                <form action="./" method="get" autocomplete="off" novalidate>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <!-- Download SVG icon from http://tabler-icons.io/i/search -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                <path d="M21 21l-6 -6" />
                            </svg>
                        </span>
                        <input type="text" value="" class="form-control" placeholder="Search…"
                            aria-label="Search in website">
                    </div>
                </form>
            </div> --}}
        </div>
    </div>
</div>
