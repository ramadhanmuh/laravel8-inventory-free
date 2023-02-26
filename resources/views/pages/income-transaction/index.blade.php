@extends('layouts.dashboard')

@section('title', "$application->name - Transaksi (Masuk)")

@section('description', 'Halaman yang berisi daftar data transaksi masuk yang dibuat.')

@section('route_name', 'Transaksi (Masuk)')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="row justify-content-end">
        <div class="col-auto">
            <a href="{{ route('income-transactions.create') }}" class="btn btn-sm btn-primary mb-3">
                <i class="fas fa-plus mr-1"></i>
                Tambah
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-white">
            <div class="row justify-content-center justify-content-lg-between align-items-center">
                <div class="col-md-7 col-lg-4 col-xl-3 mb-2 mb-md-0">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-auto">
                            <label for="sort" class="m-0 font-weight-bold">Sortir</label>
                        </div>
                        <div class="col p-md-0">
                            <select class="form-control form-control-sm" onchange="window.location.replace(this.value)">
                                <option value="{{ route('income-transactions.index', ['order_by' => 'created_at', 'order_direction' => 'desc']) }}"
                                    {{ $input['order_by'] === 'created_at' && $input['order_direction'] === 'desc' ? 'selected' : '' }}>
                                    Tanggal (Menurun)
                                </option>
                                <option value="{{ route('income-transactions.index', ['order_by' => 'created_at', 'order_direction' => 'asc']) }}"
                                    {{ $input['order_by'] === 'created_at' && $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                    Tanggal (Menaik)
                                </option>
                                <option value="{{ route('income-transactions.index', ['order_by' => 'supplier', 'order_direction' => 'desc']) }}"
                                    {{ $input['order_by'] === 'supplier' && $input['order_direction'] === 'desc' ? 'selected' : '' }}>
                                    Pemasok (Menurun)
                                </option>
                                <option value="{{ route('income-transactions.index', ['order_by' => 'supplier', 'order_direction' => 'asc']) }}"
                                    {{ $input['order_by'] === 'supplier' && $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                    Pemasok (Menaik)
                                </option>
                                <option value="{{ route('income-transactions.index', ['order_by' => 'reference_number', 'order_direction' => 'desc']) }}"
                                    {{ $input['order_by'] === 'reference_number' && $input['order_direction'] === 'desc' ? 'selected' : '' }}>
                                    Nomor Referensi (Menurun)
                                </option>
                                <option value="{{ route('income-transactions.index', ['order_by' => 'reference_number', 'order_direction' => 'asc']) }}"
                                    {{ $input['order_by'] === 'reference_number' && $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                    Nomor Referensi (Menaik)
                                </option>
                                <option value="{{ route('income-transactions.index', ['order_by' => 'remarks', 'order_direction' => 'desc']) }}"
                                    {{ $input['order_by'] === 'remarks' && $input['order_direction'] === 'desc' ? 'selected' : '' }}>
                                    Catatan (Menurun)
                                </option>
                                <option value="{{ route('income-transactions.index', ['order_by' => 'remarks', 'order_direction' => 'asc']) }}"
                                    {{ $input['order_by'] === 'remarks' && $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                    Catatan (Menaik)
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-auto col-md-5 col-lg-3">
                    <form action="{{ route('income-transactions.index') }}" method="get">
                        <input type="hidden" name="order_by" value="{{ $input['order_by'] }}">
                        <input type="hidden" name="order_direction" value="{{ $input['order_direction'] }}">
                        <div class="input-group input-group-sm">
                            <input type="search"
                                class="form-control"
                                name="keyword"
                                id="q"
                                placeholder="Pencarian"
                                value="{{ empty($input['keyword']) ? '' : $input['keyword'] }}">
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive mb-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center align-middle" style="width: 2px">No</th>
                            <th class="align-middle text-center">Tanggal</th>
                            <th class="align-middle text-center">Nomor Referensi</th>
                            <th class="align-middle">Pemasok</th>
                            <th class="align-middle">Catatan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($items->isEmpty())
                            <tr>
                                <td class="text-center" colspan="4">
                                    Data tidak ditemukan.
                                </td>
                            </tr>
                        @else
                            @foreach ($items as $item)
                                <tr>
                                    <td class="text-center align-middle">
                                        {{ $number }}
                                    </td>
                                    <td class="align-middle text-center unix-column">
                                        {{ $item->created_at }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $item->reference_number }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->supplier }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->remarks }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                              Aksi
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('income-transactions.show', $item->id) }}" class="dropdown-item">
                                                    Detail
                                                </a>
                                                <a href="{{ route('income-transactions.edit', $item->id) }}" class="dropdown-item">
                                                    Ubah
                                                </a>
                                                <form action="{{ route('income-transactions.destroy', $item->id) }}" method="post" class="dropdown-item">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-left p-0" onclick="return confirm('Data lain yang menggunakan transaksi ini akan ikut terhapus. Lanjutkan ?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $number++
                                @endphp
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-auto">
                    <nav>
                        <ul class="pagination pagination-sm">
                            @if ($input['page'] === 1)
                                <li class="page-item disabled">
                                    <a href="" class="page-link"><<</a>
                                </li>

                                <li class="page-item disabled">
                                    <a href="" class="page-link"><</a>
                                </li>

                                <li class="page-item active">
                                    <a
                                        href="{{ route('income-transactions.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}"
                                        class="page-link">
                                        1
                                    </a>
                                </li>

                                @for ($i = 2; $i <= $pageTotal; $i++)
                                    @if ($i < 4)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ route('income-transactions.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endif
                                @endfor

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >>
                                    </a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        <<
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $input['page'] - 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        <
                                    </a>
                                </li>

                                @php
                                    $pageStartNumber = $input['page'] !== $pageTotal ? $input['page'] - 1 : $input['page'] - 2;
                                    $loopingNumberStop = $input['page'] !== $pageTotal ? $input['page'] + 1 : $input['page'];
                                    $pageStartNumber = $pageStartNumber < 1 ? 1 : $pageStartNumber;
                                @endphp

                                @for ($i = $pageStartNumber; $i <= $loopingNumberStop; $i++)
                                    <li class="page-item {{ $input['page'] === $i ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ route('income-transactions.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('income-transactions.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection