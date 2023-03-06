@extends('layouts.dashboard')

@section('title', "$application->name - Ubah Pengaturan Aplikasi")

@section('description', 'Halaman formulir untuk mengubah pengaturan aplikasi.')

@section('route_name', 'Ubah Pengaturan Aplikasi')

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
<div class="card">
    <div class="card-header">
        Isi formulir
    </div>
    <div class="card-body">
        <form action="{{ url('application') }}" method="POST">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name">
                        Nama
                    </label>
                    <input type="text"
                        class="form-control"
                        name="name"
                        id="name"
                        value="{{ empty(old('name')) ? $application->name : old('name') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="copyright">
                        Copyright
                    </label>
                    <input type="text"
                        class="form-control"
                        name="copyright"
                        id="copyright"
                        value="{{ empty(old('copyright')) ? $application->copyright : old('copyright') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-block btn-primary">
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
  </div>
@endsection