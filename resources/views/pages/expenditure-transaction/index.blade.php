@extends('layouts.dashboard')

@section('title', "$application->name - Transaksi (Keluar)")

@section('description', 'Halaman yang berisi daftar data transaksi keluar yang dibuat.')

@section('route_name', 'Transaksi (Keluar)')

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
            <a href="{{ route('expenditure-transactions.create') }}" class="btn btn-sm btn-primary mb-3">
                <i class="fas fa-plus mr-1"></i>
                Tambah
            </a>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('expenditure-transactions.index') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Sortir / Saring Transaksi (Keluar)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="keyword" value="{{ $input['keyword'] }}">
                        <label for="order_by">Kolom Sortir</label>
                        <select class="form-control form-control-sm" id="order_by" name="order_by">
                            <option value="created_at"
                                {{ $input['order_by'] === 'created_at' ? 'selected' : '' }}>
                                Tanggal
                            </option>
                            <option value="picker"
                                {{ $input['order_by'] === 'picker' ? 'selected' : '' }}>
                                Pengambil
                            </option>
                            <option value="reference_number"
                                {{ $input['order_by'] === 'reference_number' ? 'selected' : '' }}>
                                Nomor Referensi
                            </option>
                            <option value="remarks"
                                {{ $input['order_by'] === 'remarks' ? 'selected' : '' }}>
                                Catatan
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order_direction">Arah Sortir</label>
                        <select name="order_direction" id="order_direction" class="form-control form-control-sm">
                            <option value="desc"
                                {{ $input['order_direction'] === 'desc' ? 'selected' : '' }}>
                                Turun
                            </option>
                            <option value="asc"
                                {{ $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                Naik
                            </option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="string_start_date">Tanggal Mulai</label>
                                <input type="date"
                                    class="form-control"
                                    value="{{ $input['string_start_date'] }}"
                                    id="string_start_date"
                                    name="string_start_date"
                                    onkeyup="document.getElementById('start_date').value = new Date(this.value).getTime() / 1000"
                                    onchange="document.getElementById('start_date').value = new Date(this.value).getTime() / 1000">
                                <input type="hidden"
                                    name="start_date"
                                    id="start_date"
                                    value="{{ $input['start_date'] }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="string_end_date">Tanggal Akhir</label>
                                <input type="date"
                                    class="form-control"
                                    value="{{ $input['string_end_date'] }}"
                                    id="string_end_date"
                                    name="string_end_date"
                                    onkeyup="document.getElementById('end_date').value = new Date(this.value).getTime() / 1000"
                                    onchange="document.getElementById('end_date').value = new Date(this.value).getTime() / 1000">
                                <input type="hidden"
                                    name="end_date"
                                    id="end_date"
                                    value="{{ $input['end_date'] }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-white">
            <div class="row justify-content-center justify-content-lg-between align-items-center">
                <div class="col-md-7 col-lg-4 mb-2 mb-lg-0">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#filterModal">
                        <i class="fas fa-filter mr-1"></i>
                        Sortir / Saring
                    </button>
                </div>
                <div class="col-lg-auto col-md-6">
                    <form action="{{ route('expenditure-transactions.index') }}" method="get">
                        <input type="hidden" name="order_by" value="{{ $input['order_by'] }}">
                        <input type="hidden" name="order_direction" value="{{ $input['order_direction'] }}">
                        <input type="hidden" name="start_date" value="{{ $input['start_date'] }}">
                        <input type="hidden" name="end_date" value="{{ $input['end_date'] }}">
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
                            <th class="align-middle text-center">Tanggal (WIB)</th>
                            <th class="align-middle text-center">Nomor Referensi</th>
                            <th class="align-middle">Pengambil</th>
                            <th class="align-middle">Catatan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($items->isEmpty())
                            <tr>
                                <td class="text-center" colspan="6">
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
                                        {{ $item->picker }}
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
                                                <a href="{{ route('expenditure-transactions.show', $item->id) }}" class="dropdown-item">
                                                    Detail
                                                </a>
                                                <a href="{{ route('expenditure-transactions.edit', $item->id) }}" class="dropdown-item">
                                                    Ubah
                                                </a>
                                                <form action="{{ route('expenditure-transactions.destroy', $item->id) }}" method="post" class="dropdown-item">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-left p-0" onclick="return confirm('Transaksi {{ $item->reference_number }} akan dihapus. Lanjutkan')">
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
                                        href="{{ route('expenditure-transactions.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}"
                                        class="page-link">
                                        1
                                    </a>
                                </li>

                                @for ($i = 2; $i <= $pageTotal; $i++)
                                    @if ($i < 4)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ route('expenditure-transactions.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endif
                                @endfor

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('expenditure-transactions.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('expenditure-transactions.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >>
                                    </a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('expenditure-transactions.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        <<
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('expenditure-transactions.index', ['page' => $input['page'] - 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
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
                                            href="{{ route('expenditure-transactions.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('expenditure-transactions.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('expenditure-transactions.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
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