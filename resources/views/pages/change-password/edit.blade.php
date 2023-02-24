@extends('layouts.dashboard')

@section('title', "$application->name - Ubah Kata Sandi")

@section('description', 'Halaman formulir untuk mengubah kata sandi pengguna.')

@section('route_name', 'Ubah Kata Sandi')

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
            Berhasil mengubah kata sandi.
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
            <form action="{{ url('change-password') }}" method="POST">
                @method('PUT')
                @csrf
                <div class="row align-items-center">
                    <div class="col-md-5 col-lg-3">
                        <label for="old_password">
                            Kata Sandi Lama
                        </label>
                    </div>
                    <div class="col-md-7 col-lg-9 mb-3">
                        <input type="password"
                            class="form-control"
                            name="old_password"
                            id="old_password">
                    </div>
                    <div class="col-md-5 col-lg-3">
                        <label for="newpassword">
                            Kata Sandi Baru
                        </label>
                    </div>
                    <div class="col-md-7 col-lg-9 mb-3">
                        <input type="password"
                            class="form-control"
                            name="newpassword"
                            id="newpassword">
                    </div>
                    <div class="col-md-5 col-lg-3">
                        <label for="newpassword_confirmation">
                            Konfirmasi Kata Sandi
                        </label>
                    </div>
                    <div class="col-md-7 col-lg-9 mb-3">
                        <input type="password"
                            class="form-control"
                            name="newpassword_confirmation"
                            id="newpassword_confirmation"
                            placeholder="Ketik Kembali Kata Sandi Baru...">
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