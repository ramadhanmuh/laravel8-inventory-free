@extends('layouts.dashboard')

@section('title', "$application->name - Pengguna - Ubah")

@section('description', 'Halaman yang berisi formulir untuk mengubah data pengguna.')

@section('route_name', 'Ubah Pengguna')

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
            <form action="{{ route('users.update', $item->id) }}" method="POST">
                @method('PUT')
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text"
                                class="form-control"
                                id="name"
                                value="{{ empty(old('name')) ? $item->name : old('name') }}"
                                name="name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text"
                                class="form-control"
                                id="username"
                                value="{{ empty(old('username')) ? $item->username : old('username') }}"
                                name="username">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email"
                                class="form-control"
                                id="email"
                                value="{{ empty(old('email')) ? $item->email : old('email') }}"
                                name="email">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Kata Sandi</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Tidak Wajib">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Jenis</label>
                            <select name="role" id="role" class="form-control">
                                <option value="">-- Pilih --</option>
                                <option value="Admin"
                                    {{ (empty(old('role')) && $item->role === 'Admin') || (!empty(old('role')) && old('role') === 'Admin') ? 'selected' : '' }}>
                                    Admin
                                </option>
                                <option value="Operator"
                                {{ (empty(old('role')) && $item->role === 'Operator') || (!empty(old('role')) && old('role') === 'Operator') ? 'selected' : '' }}>
                                    Operator
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
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