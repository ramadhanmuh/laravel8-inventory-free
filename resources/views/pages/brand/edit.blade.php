@extends('layouts.dashboard')

@section('title', "$application->name - Merek - Ubah")

@section('description', 'Halaman yang berisi formulir untuk mengubah merek.')

@section('route_name', 'Ubah Merek')

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
            <form action="{{ route('brands.update', $item->id) }}" method="POST">
                @method('PUT')
                @csrf
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
                            value="{{ empty(old('name')) ? $item->name : old('name') }}">
                    </div>
                    <div class="col-12 text-right">
                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>
                        <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection