@extends('layouts.dashboard')

@section('title', "$application->name - Satuan Barang - Tambah")

@section('description', 'Halaman yang berisi formulir untuk membuat satuan barang.')

@section('route_name', 'Tambah Satuan Barang')

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
    <div class="card">
        <div class="card-header bg-white">
            Isi Formulir
        </div>
        <div class="card-body">
            <form action="{{ route('unit-of-measurements.store') }}" method="POST">
                @method('POST')
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="row align-items-center">
                    <div class="col-md-3 col-lg-2">
                        <label for="short_name">
                            Nama Singkat
                        </label>
                    </div>
                    <div class="col-md-9 col-lg-10 mb-3">
                        <input type="text"
                            class="form-control"
                            name="short_name"
                            id="short_name"
                            value="{{ old('short_name') }}">
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <label for="full_name">
                            Nama Panjang
                        </label>
                    </div>
                    <div class="col-md-9 col-lg-10 mb-3">
                        <input type="text"
                            class="form-control"
                            name="full_name"
                            id="full_name"
                            value="{{ old('full_name') }}">
                    </div>
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection