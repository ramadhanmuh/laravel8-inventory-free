@extends('layouts.dashboard')

@section('title', "$application->name - Pengguna")

@section('description', 'Halaman yang berisi daftar data pengguna yang dibuat.')

@section('route_name', 'Pengguna')

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
    {{-- Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('users.index') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Sortir / Saring Stok</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="keyword" value="{{ $input['keyword'] }}">
                        <label for="order_by">Kolom Urut</label>
                        <select class="form-control form-control-sm" id="order_by" name="order_by">
                            <option value="name"
                                {{ $input['order_by'] === 'name' ? 'selected' : '' }}>
                                Nama
                            </option>
                            <option value="username"
                                {{ $input['order_by'] === 'username' ? 'selected' : '' }}>
                                Username
                            </option>
                            <option value="email"
                                {{ $input['order_by'] === 'email' ? 'selected' : '' }}>
                                Email
                            </option>
                            <option value="role"
                                {{ $input['order_by'] === 'role' ? 'selected' : '' }}>
                                Jenis Pengguna
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order_direction">Arah Urut</label>
                        <select name="order_direction" id="order_direction" class="form-control form-control-sm">
                            <option value="asc"
                                {{ $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                                Naik
                            </option>
                            <option value="desc"
                                {{ $input['order_direction'] === 'desc' ? 'selected' : '' }}>
                                Turun
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="role">Jenis Pengguna</label>
                        <select name="role" id="role" class="form-control form-control-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Admin">Admin</option>
                            <option value="Operator">Operator</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-auto">
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary mb-3">
                <i class="fas fa-plus mr-1"></i>
                Tambah
            </a>
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
                <div class="col-lg-auto col-md-5 col-lg-3">
                    <form action="{{ route('users.index') }}" method="get">
                        <input type="hidden" name="order_by" value="{{ $input['order_by'] }}">
                        <input type="hidden" name="order_direction" value="{{ $input['order_direction'] }}">
                        <input type="hidden" name="role" value="{{ $input['role'] }}">
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
                            <th class="align-middle">Nama</th>
                            <th class="align-middle">Username</th>
                            <th class="align-middle">Email</th>
                            <th class="align-middle text-center">Jenis</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($items->isEmpty())
                            <tr>
                                <td class="text-center" colspan="7">
                                    Data tidak ditemukan.
                                </td>
                            </tr>
                        @else
                            @foreach ($items as $item)
                                <tr>
                                    <td class="text-center align-middle">
                                        {{ $number }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->name }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->username }}
                                    </td>
                                    <td class="align-middle">
                                        {{ $item->email }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $item->role }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                                              Aksi
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="{{ route('users.show', $item->id) }}" class="dropdown-item">
                                                    Detail
                                                </a>
                                                <a href="{{ route('users.edit', $item->id) }}" class="dropdown-item">
                                                    Ubah
                                                </a>
                                                <form action="{{ route('users.destroy', $item->id) }}" method="post" class="dropdown-item">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-left p-0" onclick="return confirm('Data akan dihapus. Lanjutkan ?')">
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
                                        href="{{ route('users.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'role' => $input['role']]) }}"
                                        class="page-link">
                                        1
                                    </a>
                                </li>

                                @for ($i = 2; $i <= $pageTotal; $i++)
                                    @if ($i < 4)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ route('users.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'role' => $input['role']]) }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endif
                                @endfor

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('users.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'role' => $input['role']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('users.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'role' => $input['role']]) }}">
                                        >>
                                    </a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('users.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'role' => $input['role']]) }}">
                                        <<
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('users.index', ['page' => $input['page'] - 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'role' => $input['role']]) }}">
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
                                            href="{{ route('users.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'role' => $input['role']]) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('users.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'role' => $input['role']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('users.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'role' => $input['role']]) }}">
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