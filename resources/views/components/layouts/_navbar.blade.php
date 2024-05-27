<div class="collapse navbar-collapse" id="navbar-menu">
    <div class="navbar">
        <div class="container-xl">
            <ul class="navbar-nav">
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
                            Transaksi
                        </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            <div class="dropdown-menu-column">
                                {{-- <div class="dropend">
                                    <a class="dropdown-item dropdown-toggle {{ request()->is('transaction') || request()->is('posting') ? 'active' : '' }}"
                                        href="#sidebar-cards" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        role="button" aria-expanded="false">
                                        Loader
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('transaction.index') }}"
                                            class="dropdown-item {{ request()->is('transaction') ? 'active' : '' }}">
                                            Belum Posting
                                        </a>
                                        <a href="{{ route('posting.index') }}"
                                            class="dropdown-item {{ request()->is('posting') ? 'active' : '' }}">
                                            Sudah Posting
                                        </a>
                                    </div>
                                </div> --}}
                                <a class="dropdown-item {{ request()->is('receipt') ? 'active' : '' }}"
                                    href="{{ route('receipt.index') }}">

                                    Receipt PO
                                </a>
                                <a class="dropdown-item {{ request()->is('transfer') ? 'active' : '' }}"
                                    href="{{ route('transfer.index') }}">
                                    Transfer
                                </a>
                                <a class="dropdown-item {{ request()->is('receipt-transfer') ? 'active' : '' }}"
                                    href="{{ route('receipt-transfer.index') }}">

                                    Receipt Transfer
                                </a>
                            
                                <a class="dropdown-item {{ request()->is('issue') ? 'active' : '' }}"
                                    href="{{ route('issue.index') }}">

                                    Issue
                                </a>
                                <a class="dropdown-item {{ request()->is('adjustment') ? 'active' : '' }}"
                                    href="{{ route('adjustment.index') }}">

                                    Adjustment
                                </a>
                                {{-- <a class="dropdown-item active" href="./blank.html">
                                    Receipt
                                </a>
                                <a class="dropdown-item" href="./badges.html">
                                    Transfer
                                </a>
                                <a class="dropdown-item" href="./buttons.html">
                                    Receipt Transfer
                                </a>

                                <a class="dropdown-item" href="./colors.html">
                                    Issue
                                </a>
                                <a class="dropdown-item" href="./datagrid.html">
                                    Adjusment
                                    <span class="badge badge-sm bg-green-lt text-uppercase ms-auto">New</span>
                                </a> --}}
                            </div>
                        </div>
                    </div>
                </li>
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
                                {{-- <div class="dropend">
                                    <a class="dropdown-item dropdown-toggle {{ request()->is('transaction') || request()->is('posting') ? 'active' : '' }}"
                                        href="#sidebar-cards" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                        role="button" aria-expanded="false">
                                        Loader
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="{{ route('transaction.index') }}"
                                            class="dropdown-item {{ request()->is('transaction') ? 'active' : '' }}">
                                            Belum Posting
                                        </a>
                                        <a href="{{ route('posting.index') }}"
                                            class="dropdown-item {{ request()->is('posting') ? 'active' : '' }}">
                                            Sudah Posting
                                        </a>
                                    </div>
                                </div> --}}
                                {{-- <a class="dropdown-item {{ request()->is('loader/receive-po') ? 'active' : '' }}"
                                    href="">

                                    Receive From PO
                                </a> --}}
                                <a class="dropdown-item {{ request()->is('loader/receipt') ? 'active' : '' }}"
                                    href="{{ route('received.loader') }}">
                                    Receipt PO
                                </a>
                                <a class="dropdown-item {{ request()->is('loader/transfer') ? 'active' : '' }}"
                                    href="{{ route('transfer.loader') }}">

                                    Transfer
                                </a>
                                <a class="dropdown-item {{ request()->is('loader/receipt-transfer') ? 'active' : '' }}"
                                    href="{{ route('receipt-transfer.loader') }}">

                                    Receipt Transfer
                                </a>
                                <a class="dropdown-item {{ request()->is('loader/issue') ? 'active' : '' }}"
                                    href="{{ route('issue.loader') }}">

                                    Issue
                                </a>


                                {{-- <a class="dropdown-item {{ request()->is('loader/transfer') ? 'active' : '' }}"
                                    href="">

                                    Transfer
                                </a> --}}
                                {{-- <a class="dropdown-item {{ request()->is('loader/receive-transfer') ? 'active' : '' }}"
                                    href="">

                                    Receive From Transfer
                                </a> --}}
                                {{-- <a class="dropdown-item active" href="./blank.html">
                                Receipt
                            </a>
                            <a class="dropdown-item" href="./badges.html">
                                Transfer
                            </a>
                            <a class="dropdown-item" href="./buttons.html">
                                Receipt Transfer
                            </a>

                            <a class="dropdown-item" href="./colors.html">
                                Issue
                            </a>
                            <a class="dropdown-item" href="./datagrid.html">
                                Adjusment
                                <span class="badge badge-sm bg-green-lt text-uppercase ms-auto">New</span>
                            </a> --}}
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown" style="display: show">
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
                                <a class="dropdown-item" href="./cookie-banner.html">
                                    Company
                                </a>
                                <a class="dropdown-item" href="./activity.html">
                                    Plant
                                </a>
                                <a class="dropdown-item" href="./gallery.html">
                                    Warehouse
                                </a>
                                <a class="dropdown-item" href="./gallery.html">
                                    Fuelman
                                </a>
                                <a class="dropdown-item" href="./gallery.html">
                                    Department
                                </a>
                                <a class="dropdown-item" href="./search-results.html">
                                    Activity
                                </a>
                                <a class="dropdown-item" href="./pricing.html">
                                    Equipment
                                </a>
                                <a class="dropdown-item" href="./empty.html">
                                    Material
                                </a>                               
                                <a class="dropdown-item" href="./pricing-table.html">
                                    Unit Of Measure
                                </a>
                            </div>
                        </div>
                </li>
                <li
                    class="nav-item {{ request()->is('soh-overview') || request()->is('soh-overview/*') ? 'active' : '' }} dropdown">
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
                        <a class="dropdown-item {{ request()->is('soh-overview') || request()->is('soh-overview/*') ? 'active' : '' }}"
                            href="{{ route('soh.index') }}" rel="noopener">
                            SOH Overview
                        </a>
                        {{-- <a class="dropdown-item" href="./changelog.html">
                            Report 2
                        </a> --}}
                    </div>
                </li>
                <li class="nav-item dropdown {{ request()->is('users') ? 'active' : '' }}">
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
                        <a class="dropdown-item {{ request()->is('users') ? 'active' : '' }}"
                            href="{{ route('users.index') }}" rel="noopener">
                            User
                        </a>
                        {{-- <a class="dropdown-item" href="./changelog.html">
                            Role
                        </a> --}}
                    </div>
                </li>
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
