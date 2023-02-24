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
                        <div class="input-group input-group-sm">
                            <input type="search"
                                class="form-control"
                                name="q"
                                id="q"
                                placeholder="Pencarian"
                                value="{{ empty($request['q']) ? '' : $request['q'] }}">
                            <div class="input-group-append">
                              <button class="btn btn-outline-secondary" type="button" id="button-addon2">
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
                                <div class="row align-items-center">
                                    <div class="col">
                                        Nama
                                    </div>
                                    <div class="col-auto">
                                        <a href="{{ url('categories?order_by=name&order_direction=asc') }}" class="btn btn-sm p-1">
                                            <i class="fas fa-arrow-up text-secondary"></i>
                                        </a>
                                        <a href="{{ url('categories?order_by=name&order_direction=desc') }}" class="btn btn-sm p-1">
                                            <i class="fas fa-arrow-down text-secondary"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $num = 1
                        @endphp
                        @foreach ($items as $item)
                            <tr>
                                <td class="text-center align-middle">
                                    {{ $num }}
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
                                $num++
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-auto">
                    <nav>
                        <ul class="pagination pagination-sm">
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection