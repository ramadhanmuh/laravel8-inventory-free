<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    {{-- Sidebar - Brand  --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('dashboard') }}">
        <div class="sidebar-brand-text mx-3">{{ $applicationName }}</div>
    </a>

    {{-- Divider  --}}
    <hr class="sidebar-divider my-0">

    {{-- Nav Item - Dashboard  --}}
    <li class="nav-item {{ (request()->is('dashboard') || request()->is('dashboard/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <li class="nav-item {{ request()->is('items') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('items') }}">
            <i class="fas fa-fw fa-desktop"></i>
            <span>Master Data Barang</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ (request()->is('income-transactions') || request()->is('income-transactions/*') || request()->is('expenditure-transactions') || request()->is('expenditure-transactions/*')) ? '' : 'collapsed' }}" href="#" data-toggle="collapse" data-target="#collapseThree"
            aria-expanded="{{ (request()->is('income-transactions') || request()->is('income-transactions/*') || request()->is('expenditure-transactions') || request()->is('expenditure-transactions/*')) ? 'true' : 'false' }}" aria-controls="collapseThree">
            <i class="fas fa-fw fa-handshake"></i>
            <span>Transaksi</span>
        </a>
        <div id="collapseThree" class="collapse {{ (request()->is('income-transactions') || request()->is('income-transactions/*') || request()->is('expenditure-transactions') || request()->is('expenditure-transactions/*')) ? 'show' : '' }}" aria-labelledby="headingThree" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Transaksi:</h6>
                <a class="collapse-item {{ (request()->is('income-transactions') || request()->is('income-transactions/*')) ? 'active' : '' }}" href="{{ url('income-transactions') }}">
                    Masuk
                </a>
                <a class="collapse-item {{ (request()->is('expenditure-transactions') || request()->is('expenditure-transactions/*')) ? 'active' : '' }}" href="{{ url('expenditure-transactions') }}">
                    Keluar
                </a>
            </div>
        </div>
    </li>

    <li class="nav-item {{ (request()->is('stocks') || request()->is('stocks/*')) ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('stocks') }}">
            <i class="fas fa-fw fa-cubes"></i>
            <span>Stok Barang</span></a>
    </li>

    {{-- Divider  --}}
    <hr class="sidebar-divider d-none d-md-block">

    {{-- Sidebar Toggler (Sidebar)  --}}
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>