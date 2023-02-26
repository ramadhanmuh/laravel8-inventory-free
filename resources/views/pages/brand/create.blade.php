@extends('layouts.dashboard')

@section('title', "$application->name - Merek - Tambah")

@section('description', 'Halaman yang berisi formulir untuk membuat merek.')

@section('route_name', 'Tambah Merek')

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
            <form action="{{ route('brands.store') }}" method="POST">
                @method('POST')
                @csrf
                <input type="hidden" name="id" value="{{ $id }}">
                <div class="row align-items-center">
                    <div class="col-md-2 col-lg-1">
                        <label for="name">
                            Nama
                        </label>
                    </div>
                    <div class="col-md-10 col-lg-11 mb-3">
                        <input type="text"
                            class="form-control"
                            name="name"
                            id="name"
                            value="{{ old('name') }}">
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