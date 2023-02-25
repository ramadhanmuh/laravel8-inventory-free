@extends('layouts.dashboard')

@section('title', "$application->name - Satuan Barang")

@section('description', 'Halaman yang berisi daftar satuan barang yang dibuat.')

@section('route_name', 'Satuan Barang')

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
            <a href="{{ route('unit-of-measurements.create') }}" class="btn btn-sm btn-primary mb-3">
                <i class="fas fa-plus mr-1"></i>
                Tambah
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-white">
            <div class="row justify-content-center justify-content-lg-between align-items-center">
                <div class="col-md-7 col-lg-4 mb-2 mb-md-0">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-auto">
                            <label for="sort" class="m-0 font-weight-bold">Sortir</label>
                        </div>
                        <div class="col p-md-0">
                            <select class="form-control form-control-sm" onchange="window.location.replace(this.value)">
                                <option value="{{ route('unit-of-measurements.index', ['order_by' => 'full_name', 'order_direction' => 'asc']) }}"
                                    {{ $input['order_by'] === 'full_name' && $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                    Nama Panjang (Menaik)
                                </option>
                                <option value="{{ route('unit-of-measurements.index', ['order_by' => 'full_name', 'order_direction' => 'asc']) }}"
                                    {{ $input['order_by'] === 'full_name' && $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                    Nama Panjang (Menurun)
                                </option>
                                <option value="{{ route('unit-of-measurements.index', ['order_by' => 'short_name', 'order_direction' => 'asc']) }}"
                                    {{ $input['order_by'] === 'short_name' && $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                    Nama Singkat (Menaik)
                                </option>
                                <option value="{{ route('unit-of-measurements.index', ['order_by' => 'short_name', 'order_direction' => 'asc']) }}"
                                    {{ $input['order_by'] === 'short_name' && $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                    Nama Singkat (Menurun)
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-auto col-md-5 col-lg-3">
                    <form action="{{ route('unit-of-measurements.index') }}" method="get">
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
                            <th class="text-center align-middle">ID</th>
                            <th class="align-middle">Nama Singkat</th>
                            <th class="align-middle">Nama Panjang</th>
                            <th class="text-center align-middle">Aksi</th>
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
                                    <td class="text-center align-middle">
                                        {{ $item->id }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->short_name }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->full_name }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('unit-of-measurements.edit', $item->id) }}" class="btn btn-warning btn-sm my-1">
                                            Ubah
                                        </a>
                                        <form action="{{ route('unit-of-measurements.destroy', $item->id) }}" method="post" class="d-inline my-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Data lain yang menggunakan satuan barang ini akan ikut terhapus. Lanjutkan ?')">
                                                Hapus
                                            </button>
                                        </form>
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
                                        href="{{ route('unit-of-measurements.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}"
                                        class="page-link">
                                        1
                                    </a>
                                </li>

                                @for ($i = 2; $i <= $pageTotal; $i++)
                                    @if ($i < 4)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ route('unit-of-measurements.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endif
                                @endfor

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('unit-of-measurements.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('unit-of-measurements.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >>
                                    </a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('unit-of-measurements.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        <<
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('unit-of-measurements.index', ['page' => $input['page'] - 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
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
                                            href="{{ route('unit-of-measurements.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('unit-of-measurements.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('unit-of-measurements.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
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