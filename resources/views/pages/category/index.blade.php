@extends('layouts.dashboard')

@section('title', "$application->name - Kategori")

@section('description', 'Halaman yang berisi daftar kategori yang dibuat.')

@section('route_name', 'Kategori')

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
            <a href="{{ route('categories.create') }}" class="btn btn-sm btn-primary mb-3">
                <i class="fas fa-plus mr-1"></i>
                Tambah
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-white">
            <div class="row justify-content-end">
                <div class="col-auto col-md-5 col-lg-3">
                    <form action="{{ route('categories.index') }}" method="get">
                        <input type="hidden" name="page" value="{{ $input['page'] }}">
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
                            <th class="text-center" style="width: 2px">No</th>
                            <th class="text-center">ID</th>
                            <th>
                                Nama
                                <a href="{{ route('categories.index', ['page' => 1, 'order_by' => 'name', 'order_direction' => 'desc', 'keyword' => $input['keyword']]) }}"
                                    class="btn btn-sm p-0 px-1 float-right">
                                    <i class="fas fa-arrow-down {{ $input['order_by'] === 'name' && $input['order_direction'] === 'desc' ? 'text-primary' : 'text-secondary' }}"></i>
                                </a>
                                <a href="{{ route('categories.index', ['page' => 1, 'order_by' => 'name', 'order_direction' => 'asc', 'keyword' => $input['keyword']]) }}"
                                    class="btn btn-sm p-0 px-1 float-right">
                                    <i class="fas fa-arrow-up {{ ($input['order_by'] === 'name' || empty($input['order_by'])) && ($input['order_direction'] === 'asc' || empty($input['order_direction'])) ? 'text-primary' : 'text-secondary' }}"></i>
                                </a>
                            </th>
                            <th class="text-center">Aksi</th>
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
                                        {{ $item->name }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="{{ route('categories.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                            Ubah
                                        </a>
                                        <form action="{{ route('categories.destroy', $item->id) }}" method="post" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
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
                                        href="{{ route('categories.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}"
                                        class="page-link">
                                        1
                                    </a>
                                </li>

                                @for ($i = 2; $i <= $pageTotal; $i++)
                                    @if ($i < 4)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ route('categories.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                                {{ $i }}
                                            </a>
                                        </li>
                                    @endif
                                @endfor

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('categories.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('categories.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >>
                                    </a>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('categories.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        <<
                                    </a>
                                </li>

                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ route('categories.index', ['page' => $input['page'], 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        <
                                    </a>
                                </li>

                                @php
                                    $pageStartNumber = $input['page'] !== $pageTotal ? $input['page'] - 1 : $input['page'] - 2;
                                    $loopingNumberStop = $input['page'] !== $pageTotal ? $input['page'] + 1 : $input['page'];
                                @endphp

                                @for ($i = $pageStartNumber; $i <= $loopingNumberStop; $i++)
                                    <li class="page-item {{ $input['page'] === $i ? 'active' : '' }}">
                                        <a class="page-link"
                                            href="{{ route('categories.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('categories.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ route('categories.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword']]) }}">
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