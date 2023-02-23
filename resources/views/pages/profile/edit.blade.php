@extends('layouts.dashboard')

@section('title', "$application->name - Ubah Profil")

@section('description', 'Halaman formulir untuk mengubah profil pengguna.')

@section('route_name', 'Ubah Profil')

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
    <div class="card-header">
        Isi formulir
    </div>
    <div class="card-body">
        <form action="{{ url('profile') }}" method="POST">
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
                        value="{{ empty(old('name')) ? auth()->user()->name : old('name') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="username">
                        Username
                    </label>
                    <input type="text"
                        class="form-control"
                        name="username"
                        id="username"
                        value="{{ empty(old('username')) ? auth()->user()->username : old('username') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email">
                        Email
                    </label>
                    <input type="email"
                        class="form-control"
                        name="email"
                        id="email"
                        value="{{ empty(old('email')) ? auth()->user()->email : old('email') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password">
                        Kata Sandi
                    </label>
                    <input type="password"
                        class="form-control"
                        name="password"
                        id="password"
                        placeholder="Isi kata sandi...">
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