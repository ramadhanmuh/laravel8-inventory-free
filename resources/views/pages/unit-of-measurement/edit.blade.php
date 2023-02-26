@extends('layouts.dashboard')

@section('title', "$application->name - Satuan Barang - Ubah")

@section('description', 'Halaman yang berisi formulir untuk mengubah satuan barang.')

@section('route_name', 'Ubah Satuan Barang')

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
            <form action="{{ route('unit-of-measurements.update', $item->id) }}" method="POST">
                @method('PUT')
                @csrf
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
                            value="{{ empty(old('short_name')) ? $item->short_name : old('short_name') }}">
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
                            value="{{ empty(old('full_name')) ? $item->full_name : old('full_name') }}">
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